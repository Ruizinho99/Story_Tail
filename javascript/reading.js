let currentPage = 1;
const pdfViewer = document.getElementById("pdfViewer");
const pageInfo = document.getElementById("pageInfo");

document.getElementById("nextPage").addEventListener("click", () => {
    currentPage++;
    updatePage();
});

document.getElementById("prevPage").addEventListener("click", () => {
    if (currentPage > 1) {
        currentPage--;
        updatePage();
    }
});

function updatePage() {
    // Atualiza o PDF para a página correta
    pdfViewer.src = `livro.pdf#page=${currentPage}`;
    pageInfo.textContent = `Página: ${currentPage}`;
}

// Função para o botão do audiobook
document.getElementById("audioBookBtn").addEventListener("click", () => {
    alert("O Audiobook será tocado!");
});
