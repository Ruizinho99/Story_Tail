<?php include 'db_connection.php';
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
        position: relative; /* Para permitir o posicionamento dos botões */
    }

    .controls {
        position: absolute;
        top: 50%; /* Centraliza os botões na altura */
        transform: translateY(-50%); /* Ajusta para centralizar exatamente */
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

    /* Botão de retroceder à esquerda */
    #prev {
        position: absolute;
        left: 10px; /* Posiciona à esquerda */
    }

    /* Botão de avançar à direita */
    #next {
        position: absolute;
        right: 10px; /* Posiciona à direita */
    }

    .controls img {
        width: 30px;
    }

    /* Botões invisíveis para ocupar a altura toda */
    .invisible-btn {
        position: absolute;
        top: 0;
        width: 10px; /* Largura bem pequena para os botões invisíveis */
        height: 100%; /* Ocupa toda a altura do PDF */
        background-color: transparent;
        opacity: 0;
        z-index: 5; /* Garante que fiquem por cima do PDF */
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

    /* Media Queries para garantir responsividade em diferentes tamanhos de tela */
    @media (max-width: 768px) {
        .reader {
            max-width: 100%;
            padding: 15px;
        }

        .controls button {
            width: 40px;
            height: 40px;
        }

        /* Ajuste para telas com altura menor */
        @media (max-height: 600px) {
            .reader {
                padding: 10px;
            }

            .controls button {
                width: 30px;
                height: 30px;
            }
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

        /* Ajuste para telas com altura menor */
        @media (max-height: 500px) {
            .reader {
                padding: 5px;
            }

            .controls button {
                width: 25px;
                height: 25px;
            }
        }
    }
</style>

</head>

<body>

    <?php include 'header_choose.php'; ?>
    <div class="container">
        <div class="book-container">
            <h1 class="h4"><?= htmlspecialchars($book['title']) ?></h1> <!-- Título reduzido -->
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
            <button id="close-reader" class="btn btn-danger mt-3">Fechar</button>
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

        let pdfDoc = null;
        let pageNum = 1;
        let pageIsRendering = false;
        let pageNumPending = null;

        // Calcular a escala dinâmica com base na largura da tela
        const scale = window.innerWidth < 768 ? 0.6 : 0.8; // Escala mais agressiva para reduzir o tamanho

        function renderPage(num) {
            pageIsRendering = true;

            pdfDoc.getPage(num).then(page => {
                const viewport = page.getViewport({
                    scale
                });
                
                // Limitar a altura do PDF para 80% da altura da janela
                const maxHeight = window.innerHeight * 0.8;
                const ratio = maxHeight / viewport.height;

                // Ajuste a escala caso a altura ultrapasse o limite
                const adjustedScale = scale * ratio;

                const adjustedViewport = page.getViewport({
                    scale: adjustedScale
                });

                canvas.width = adjustedViewport.width;
                canvas.height = adjustedViewport.height;

                const renderCtx = {
                    canvasContext: ctx,
                    viewport: adjustedViewport
                };

                page.render(renderCtx).promise.then(() => {
                    pageIsRendering = false;

                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });

                // Controlar visibilidade dos botões
                prevPage.style.display = num === 1 ? 'none' : 'inline-block';
                nextPage.style.display = num === pdfDoc.numPages ? 'none' : 'inline-block';
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

        closeReader.addEventListener('click', () => {
            reader.style.display = 'none';
        });

        prevPage.addEventListener('click', showPrevPage);
        nextPage.addEventListener('click', showNextPage);

        // Carregar o livro automaticamente
        openReader('<?= $book_url ?>');
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    

<?php include 'footer.html'; ?>
</body>

</html>     