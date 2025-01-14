<?php
// Conexão com o banco de dados
include_once("db_connection.php");

// Consulta para obter os livros
$sql = "SELECT id, title, author, cover_url FROM books ORDER BY RAND() LIMIT 20"; // Exibe até 20 livros
$result = $conn->query($sql);

// Verifica se há livros disponíveis
if ($result->num_rows > 0): 
?>
<link rel="stylesheet" href="Styles/carousel.css">
<div class="container my-5">
    <h2 class="text-center mb-4">Discover More Books</h2>
    <!-- Swiper Container -->
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <?php while ($book = $result->fetch_assoc()): ?>
                <div class="swiper-slide">
                    <div class="card text-center" style="width: 12rem; margin: 0 auto;">
                        <img src="<?= !empty($book['cover_url']) ? htmlspecialchars($book['cover_url']) : 'uploads/default-book.png' ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($book['title']) ?>" 
                             style="height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="card-text">By <?= htmlspecialchars($book['author']) ?></p>
                            <a href="info_book.php?book_id=<?= $book['id'] ?>" class="btn btn-read-now">Read Now</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <!-- Botões de navegação -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>
<?php endif; ?>

<!-- Swiper.js -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const swiper = new Swiper(".mySwiper", {
        slidesPerView: 4, // Mostra 4 livros por vez
        spaceBetween: 20, // Espaço entre os slides
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        grabCursor: true, // Permite click and drag
        breakpoints: {
            640: { slidesPerView: 1 }, // 1 slide em telas pequenas
            768: { slidesPerView: 2 }, // 2 slides em tablets
            1024: { slidesPerView: 3 }, // 3 slides em telas médias
            1200: { slidesPerView: 4 }, // 4 slides em telas maiores
        },
    });
});
</script>

