<?php
include 'db_connection.php';
session_start(); // Inicia a sessão

// Pega o ID do usuário da sessão
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$user_id) {
    // Se o usuário não estiver logado, exibe uma mensagem ou redireciona
    echo "Você precisa estar logado para continuar.";
    exit;
}

// Pega o ID do livro da URL
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;

if ($book_id > 0) {
    // Consulta para pegar os detalhes do livro
    $sql = "SELECT 
                b.title,
                b.author,
                b.description,
                b.cover_url,
                b.publication_year,
                b.age_group,
                b.access_level,
                b.book_url
            FROM books b
            WHERE b.id = ?";

    // Preparar a consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        // Concatenar o caminho completo do PDF
        $book_url = 'uploads/books/' . htmlspecialchars($book['book_url']);
    } else {
        echo "Livro não encontrado.";
        exit;
    }
} else {
    echo "ID de livro inválido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leitura de <?= htmlspecialchars($book['title']) ?></title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=lock" />
    <link rel="stylesheet" href="Styles/headers.css">
    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="Styles/index.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .book-container {
            text-align: center;
            margin-top: 20px;
        }

        .reader {
            display: none;
            flex-direction: column;
            align-items: center;
            margin: 0px auto;
            width: 110%;
            max-width: 400px;
            padding: 2px;
            position: relative;
        }

        .controls {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .controls button {
            margin: 5px;
            border: none;
            background-color: transparent;
        }

        #prev {
            position: absolute;
            left: 10px;
        }

        #next {
            position: absolute;
            right: 10px;
        }

        .controls img {
            width: 30px;
        }

        .invisible-btn {
            position: absolute;
            top: 0;
            width: 10px;
            height: 100%;
            background-color: transparent;
            opacity: 0;
            z-index: 5;
        }

        #prev-invisible {
            left: 0;
        }

        #next-invisible {
            right: 0;
        }

        canvas {
            width: 100%;
            height: auto;
            max-width: 100%;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .reader {
                max-width: 100%;
                padding: 15px;
            }

            .controls button {
                width: 40px;
                height: 40px;
            }
        }

        @media (max-width: 480px) {
            .reader {
                padding: 10px;
            }

            .controls button {
                width: 35px;
                height: 35px;
            }
        }
    </style>
</head>

<body>

    <?php include 'header_choose.php'; ?>
    <div class="container">
        <div class="book-container">
            <h1 class="h4"><?= htmlspecialchars($book['title']) ?></h1>
        </div>

        <div class="reader" id="reader">
            <canvas id="pdf-render" class="pdf-canvas"></canvas>
            <div class="controls">
                <button id="prev" style="display: none;">
                    <img src="https://img.icons8.com/ios-glyphs/30/000000/chevron-left.png" alt="Página Anterior">
                </button>
                <button id="next">
                    <img src="https://img.icons8.com/ios-glyphs/30/000000/chevron-right.png" alt="Próxima Página">
                </button>
            </div>
            <div id="page-counter" class="mt-3">Página <span id="current-page">1</span> de <span id="total-pages"></span></div>
            <button id="close-reader" class="btn btn-danger mt-3"><a href="javascript:history.back()" style="text-decoration: none; color:white">Fechar</a></button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script>
        const reader = document.getElementById('reader');
        const canvas = document.getElementById('pdf-render');
        const ctx = canvas.getContext('2d');
        const closeReader = document.getElementById('close-reader');
        const prevPage = document.getElementById('prev');
        const nextPage = document.getElementById('next');
        const currentPage = document.getElementById('current-page');
        const totalPages = document.getElementById('total-pages');

        let pdfDoc = null;
        let pageNum = 1;
        let pageIsRendering = false;
        let pageNumPending = null;

        const scale = window.innerWidth < 768 ? 0.6 : 0.8;

        function renderPage(num) {
            pageIsRendering = true;

            pdfDoc.getPage(num).then(page => {
                const viewport = page.getViewport({
                    scale
                });

                canvas.width = viewport.width;
                canvas.height = viewport.height;

                const renderCtx = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                page.render(renderCtx).promise.then(() => {
                    pageIsRendering = false;

                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });

                prevPage.style.display = num === 1 ? 'none' : 'inline-block';
                nextPage.style.display = num === pdfDoc.numPages ? 'none' : 'inline-block';

                // Atualiza o contador de páginas
                currentPage.textContent = num;
                totalPages.textContent = pdfDoc.numPages;
            });
        }

        function queueRenderPage(num) {
            if (pageIsRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        function showPrevPage() {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        }

        function showNextPage() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        }

        function openReader(pdfUrl) {
            pdfjsLib.getDocument(pdfUrl).promise.then(pdfDoc_ => {
                pdfDoc = pdfDoc_;
                reader.style.display = 'flex';
                renderPage(pageNum);
            });
        }

        function saveProgress() {
            const data = {
                user_id: <?= $user_id ?>, // Usando o ID do usuário dinamicamente
                book_id: <?= $book_id ?>,
                current_page: pageNum,
                total_pages: pdfDoc.numPages // Envia o número total de páginas
            };

            fetch('save_progress.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message || data.error);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        closeReader.addEventListener('click', () => {
            saveProgress();
            reader.style.display = 'none';
        });

        prevPage.addEventListener('click', () => {
            saveProgress();
            showPrevPage();
        });

        nextPage.addEventListener('click', () => {
            saveProgress();
            showNextPage();
        });

        openReader('<?= $book_url ?>');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'footer.html'; ?>
</body>

</html>