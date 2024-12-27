<?php
include_once("db_connection.php");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Função para obter livros de acordo com a categoria
function getBooks($category)
{
    global $conn;

    // SQL diferente baseado na "categoria"
    if ($category == 'New Books') {
        $sql = "SELECT id, title, cover_url, access_level 
                FROM books 
                WHERE is_active = 1 
                ORDER BY added_at DESC"; // Mais recentes primeiro
    } elseif ($category == 'Most Popular') {
        // Livros mais populares nos últimos 3 meses
        $sql = "
            SELECT 
                b.id, 
                b.title, 
                b.cover_url, 
                b.access_level, 
                AVG(r.rating) AS average_rating, 
                COUNT(r.id) AS total_reviews
            FROM books b
            JOIN ratings r ON b.id = r.book_id
            WHERE r.rating_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) 
            AND b.is_active = 1
            GROUP BY b.id
            ORDER BY average_rating DESC, total_reviews DESC
            LIMIT 5"; // Limite para os 5 melhores
    } else {
        $sql = "SELECT id, title, cover_url, access_level 
                FROM books 
                WHERE is_active = 1 
                ORDER BY RAND()"; // Ordem aleatória
    }

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $books = [];
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
        return $books;
    } else {
        return [];
    }
}

// Verificar categoria selecionada
$category = isset($_GET['category']) ? $_GET['category'] : "New Books";
$books = getBooks($category);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=lock" />
    <link rel="stylesheet" href="Styles/headers.css">
    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="Styles/index.css">
    <title>StoryTail</title>
</head>

<body>

    <?php 
    include_once 'header_choose.php';
    ?>

    <div class="background-container">
        <!-- Removido o cabeçalho -->

        <section class="search-section">
            <div class="search-container">
                <h2>Find a book</h2>
                <form action="search_results.php" method="GET">
                    <div class="search-bar">             
                        <input type="text" name="query" placeholder="eg. title, type..." />
                        <button type="submit">
                            <span class="material-icons">search</span>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <!-- Seção New Books -->
    <section class="new-books-section">
        <?php
        echo '<link rel="stylesheet" type="text/css" href="styles/new_books.css">';
        ?>

        <div class="books-section">
            <div class="category-selector">
                <a href="?category=New Books" class="<?= $category == 'New Books' ? 'active' : '' ?>">New Books</a>
                <a href="?category=Our Picks" class="<?= $category == 'Our Picks' ? 'active' : '' ?>">Our Picks</a>
                <a href="?category=Most Popular" class="<?= $category == 'Most Popular' ? 'active' : '' ?>">Most Popular</a>
            </div>

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-10">
                        <!-- Adicionando classes responsivas -->
                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
                            <?php if (!empty($books)): ?>
                                <?php foreach ($books as $book): ?>
                                    <div class="col d-flex justify-content-center">
                                        <div class="card">
                                            <img src="<?= htmlspecialchars($book['cover_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>">

                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>

                                                <div class="d-flex justify-content-center">
                                                    <?php if ($book['access_level'] == 0): ?>
                                                        <a href="info_book.php?book_id=<?= $book['id'] ?>" class="btn">Read</a>
                                                    <?php elseif ($book['access_level'] == 1): ?>
                                                        <a href="info_book.php?book_id=<?= $book['id'] ?>" class="btn">
                                                            <span class="material-symbols-outlined">lock</span>
                                                            Preview
                                                        </a>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Exibir average_rating e total_reviews -->
                                                <?php if (isset($book['average_rating'])): ?>
                                                    <div class="rating-info">
                                                        <span class="text-warning">
                                                            <?php
                                                            $average_rating = round($book['average_rating'], 1);
                                                            for ($i = 1; $i <= 5; $i++) {
                                                                echo $i <= $average_rating
                                                                    ? '<i class="fas fa-star"></i>'
                                                                    : '<i class="far fa-star"></i>';
                                                            }
                                                            ?>
                                                        </span>
                                                        <span><?= number_format($book['average_rating'], 1) ?>/5</span>
                                                        <span>(<?= $book['total_reviews'] ?> reviews)</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Nenhum livro encontrado para a categoria selecionada.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.html'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
