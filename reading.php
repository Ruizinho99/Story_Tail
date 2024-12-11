<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leitura de Livros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }

        .reader {
            position: fixed;
            top: 5%;
            left: 10%;
            width: 80%;
            height: 90%;
            background-color: #fff;
            border: 2px solid #ffa500;
            border-radius: 8px;
            display: none;
            flex-direction: column;
            align-items: center;
            padding: 10px;
        }

        .pdf-canvas {
            width: auto;
            height: 90%;
            max-width: 100%;
            max-height: 100%;
            border: none;
        }

        .reader .controls {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .reader button {
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        .reader button img {
            width: 24px;
            height: 24px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .book-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            align-items: center;
        }

        .book-link {
            padding: 10px;
            border: 2px solid #ffa500;
            background-color: #fff;
            border-radius: 5px;
            text-decoration: none;
            color: #000;
            transition: transform 0.2s;
        }

        .book-link:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="book-container">
        <h1>Escolha um Livro para Ler</h1>
        <button class="book-link" onclick="openReader('uploads/books/Eric_Carle_Brown_Bear_What_Do_You_See.pdf')">Ler Livro</button>
    </div>

    <div class="reader" id="reader">
        <canvas id="pdf-render" class="pdf-canvas"></canvas>
        <div class="controls">
            <button id="prev" style="display: none;"><img src="https://img.icons8.com/ios-glyphs/30/000000/chevron-left.png" alt="Página Anterior"></button>
            <button id="next"><img src="https://img.icons8.com/ios-glyphs/30/000000/chevron-right.png" alt="Próxima Página"></button>
        </div>
        <button id="close-reader" style="margin-top: 10px;">Fechar</button>
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

        const scale = 1.5;

        function renderPage(num) {
            pageIsRendering = true;

            pdfDoc.getPage(num).then(page => {
                const viewport = page.getViewport({ scale });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                const renderCtx = {
                    canvasContext: ctx,
                    viewport
                };

                page.render(renderCtx).promise.then(() => {
                    pageIsRendering = false;

                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });

                // Control visibility of buttons
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
    </script>
</body>
</html>
