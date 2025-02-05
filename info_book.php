<?php
session_start(); // Iniciar sessão

include_once("db_connection.php");

// Verificar se o parâmetro book_id foi passado
if (!isset($_GET['book_id']) || empty($_GET['book_id'])) {
    die("Erro: Livro não especificado.");
}

$book_id = intval($_GET['book_id']); // Sanitizar entrada

// Obter informações do livro
$sql = "
    SELECT 
        b.title,
        b.author,
        b.description,
        b.cover_url,
        b.publication_year,
        b.age_group,
        b.access_level,
        AVG(r.rating) AS average_rating,
        COUNT(r.id) AS rating_count,
        (SELECT rating FROM ratings WHERE user_id = ? AND book_id = b.id) AS user_rating,
        a.id AS author_id, -- Obter o ID do autor
        CONCAT(a.first_name, ' ', a.last_name) AS author_name -- Nome completo do autor
    FROM books b
    LEFT JOIN authors a ON b.author = CONCAT(a.first_name, ' ', a.last_name) -- Relacionar pelo nome completo
    LEFT JOIN ratings r ON b.id = r.book_id
    WHERE b.id = ?
    GROUP BY b.id;
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $_SESSION['user_id'], $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Erro: Livro não encontrado.");
}

$book = $result->fetch_assoc();

// Verificar se o livro é favorito para o usuário atual
$is_favorite_sql = "SELECT * FROM favourite_books WHERE user_id = ? AND book_id = ?";
$stmt_favorite = $conn->prepare($is_favorite_sql);
$stmt_favorite->bind_param("ii", $_SESSION['user_id'], $book_id);
$stmt_favorite->execute();
$is_favorite = $stmt_favorite->get_result()->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']) ?> - Detalhes</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="Styles/info_book.css">
    <link rel="stylesheet" href="Styles/headers.css">
</head>

<body>
    <?php include_once 'header_choose.php'; ?>
    <section class="book-details py-5">
        <div class="container">
            <h1 class="section-title text-center mb-4">ABOUT THE BOOK</h1>
            <div class="row align-items-center">
                <!-- Imagem do livro -->
                <div class="col-md-4 text-center">
                    <img src="<?= htmlspecialchars($book['cover_url']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="img-fluid book-cover">
                </div>
                <!-- Detalhes do livro -->
                <div class="col-md-8">
                    <h2 class="book-title"><?= htmlspecialchars($book['title']) ?></h2>
                    <p class="book-author">From: <a href="author_details.php?author_id=<?= htmlspecialchars($book['author_id']) ?>" class="author-name"><?= htmlspecialchars($book['author']) ?></a></p>
                    <!-- Rating -->
                    <div class="ratings mb-3">
                        <div id="stars-container" class="stars text-warning" data-book-id="<?= $book_id ?>" data-average-rating="<?= $book['average_rating'] ?>" data-user-rating="<?= $user_rating ?? 0 ?>">
                            <?php
                            $average_rating = floatval($book['average_rating']);
                            for ($i = 1; $i <= 5; $i++) {
                                echo '<i class="far fa-star" data-value="' . $i . '"></i>';
                            }
                            ?>
                            <span class="rating-value"><?= number_format($average_rating, 1) ?>/5</span>
                            <span class="rating-count">(<?= $book['rating_count'] ?> ratings)</span>
                        </div> 
                    </div>

                    <!-- Informações adicionais -->
                    <div class="book-meta mb-3">
                        <span class="meta-item me-3">
                            <i class="material-icons align-middle">menu_book</i> Age Group: <?= htmlspecialchars($book['age_group']) ?>
                        </span>
                        <span class="meta-item">
                            <i class="material-icons align-middle">schedule</i> Published: <?= htmlspecialchars($book['publication_year']) ?>
                        </span>
                    </div>
                    <!-- Descrição do livro -->
                    <p class="book-description">
                        <?= htmlspecialchars($book['description']) ?>
                    </p>
                    <!-- Botão de leitura -->
                    <?php if (!isset($_SESSION['user_id'])) : ?>
                        <!-- Usuário não logado: Redirecionar para login -->
                        <a href="login.php" class="btn btn-primary">READ NOW</a>
                    <?php else : ?>
                        <?php if ($book['access_level'] == 0) : ?>
                            <!-- Livro público: Disponível para todos -->
                            <a href="reading.php?book_id=<?= $book_id ?>" class="btn btn-primary">READ NOW</a>
                        <?php elseif ($_SESSION['user_type_id'] == 3 || $_SESSION['user_type_id'] == 1) : ?>
                            <!-- Livro premium: Disponível para premium ou admin -->
                            <a href="reading.php?book_id=<?= $book_id ?>" class="btn btn-primary">READ NOW</a>
                        <?php else : ?>
                            <!-- Usuário free: Redirecionar para planos -->
                            <a href="plan.php" class="btn btn-warning" onclick="alert('Este livro está protegido. Assine um plano premium para acessar.');">READ NOW</a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Ícone de favorito -->
                    <button id="bookmark-button" class="btn btn-outline-secondary <?= $is_favorite ? 'active' : '' ?>" data-book-id="<?= $book_id ?>">
                        <i class="bi <?= $is_favorite ? 'bi-bookmark-fill' : 'bi-bookmark' ?>"></i>
                    </button>


                </div>
            </div>
        </div>
    </section>
    
    <?php include 'book_carousel.php'; ?>
    <script>
        document.getElementById('bookmark-button').addEventListener('click', function () {
            const bookId = this.dataset.bookId;
            const icon = this.querySelector('i');
            const button = this;

            fetch('toggle_favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ book_id: bookId }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'added') {
                        icon.classList.remove('bi-bookmark');
                        icon.classList.add('bi-bookmark-fill');
                        button.classList.add('active');
                    } else if (data.status === 'removed') {
                        icon.classList.remove('bi-bookmark-fill');
                        icon.classList.add('bi-bookmark');
                        button.classList.remove('active');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>

    <script src="js/rating.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'footer.html'; ?>
</body>

</html>
