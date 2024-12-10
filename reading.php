    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PDF Book Reader</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
        <style>
            .pdf-container {
                height: 80vh;
                overflow: hidden;
                position: relative;
                border: 1px solid #ddd;
                margin-bottom: 20px;
            }
            .pdf-page {
                display: none;
                width: 100%;
            }
            .pdf-controls {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 10px;
            }
            .pdf-controls button {
                margin: 0 10px;
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <h1 class="text-center">PDF Book Reader</h1>
            <div class="row">
                <div class="col-md-12">
                    <!-- Upload PDF -->
                    <input type="file" id="pdf-upload" class="form-control mb-4" accept="application/pdf" />
                    
                    <!-- PDF Display Area -->
                    <div class="pdf-container" id="pdf-container">
                        <div id="pdf-pages"></div>
                    </div>

                    <!-- PDF Controls -->
                    <div class="pdf-controls">
                        <button id="prev-page" class="btn btn-primary">Previous</button>
                        <span id="page-number" class="text-center"></span>
                        <button id="next-page" class="btn btn-primary">Next</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let pdfDoc = null;
            let currentPage = 1;
            let totalPages = 0;
            const pdfContainer = document.getElementById('pdf-container');
            const pdfPages = document.getElementById('pdf-pages');
            const pageNumberElement = document.getElementById('page-number');

            // Load PDF
            document.getElementById('pdf-upload').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type === 'application/pdf') {
                    const reader = new FileReader();
                    reader.onload = function() {
                        const pdfData = new Uint8Array(reader.result);
                        pdfjsLib.getDocument(pdfData).promise.then(function(pdf) {
                            pdfDoc = pdf;
                            totalPages = pdf.numPages;
                            currentPage = 1;
                            renderPage(currentPage);
                        });
                    };
                    reader.readAsArrayBuffer(file);
                }
            });

            // Render a page from PDF
            function renderPage(pageNum) {
                pdfDoc.getPage(pageNum).then(function(page) {
                    const scale = 1.5;
                    const viewport = page.getViewport({ scale: scale });

                    // Create canvas to render PDF page
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    pdfPages.innerHTML = ''; // Clear existing page content
                    pdfPages.appendChild(canvas);

                    // Render page on canvas
                    page.render({ canvasContext: context, viewport: viewport }).promise.then(function() {
                        pageNumberElement.textContent = `Page ${pageNum} of ${totalPages}`;
                    });
                });
            }

            // Navigate to previous page
            document.getElementById('prev-page').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    renderPage(currentPage);
                }
            });

            // Navigate to next page
            document.getElementById('next-page').addEventListener('click', function() {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderPage(currentPage);
                }
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
