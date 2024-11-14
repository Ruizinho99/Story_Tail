<?php
include 'db_connection.php';
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

function getBooks($category) {
    global $conn;
    
    // SQL diferente baseado na "categoria"
    if ($category == 'New Books') {
        $sql = "SELECT id, title, cover_url, added_at FROM books WHERE is_active = 1 ORDER BY added_at DESC"; // Mais recentes primeiro
    } else {
        $sql = "SELECT id, title, cover_url, added_at FROM books WHERE is_active = 1 ORDER BY RAND()"; // Ordem aleatória
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
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Exemplo com iframe</title>
</head>
<body>
    <!-- Contêiner de fundo para header e search-section -->
    <div class="background-container">
        <?php include 'sl_header.html'; ?>

        <section class="search-section">
            <div class="search-container">
                <h2>Find a book</h2>
                <div class="search-bar">
                    <input type="text" placeholder="eg. title, type..." />
                    <button type="submit">
                        <span class="material-icons">search</span>
                    </button>
                </div>
            </div>
        </section>
    </div>

    <!-- Seção New Books (sem imagem de fundo) -->
    <section class="new-books-section">
        <?php
        // Carregar estilos específicos da seção New Books
        echo '<link rel="stylesheet" type="text/css" href="styles/new_books.css">';

        // Definir a categoria selecionada
        $category = isset($_GET['category']) ? $_GET['category'] : "New Books";
        $books = getBooks($category);
        ?>

        <div class="books-section">
            <div class="category-selector">
                <a href="?category=New Books" class="<?= $category == 'New Books' ? 'active' : '' ?>">New Books</a>
                <a href="?category=Our Picks" class="<?= $category == 'Our Picks' ? 'active' : '' ?>">Our Picks</a>
                <a href="?category=Most Popular" class="<?= $category == 'Most Popular' ? 'active' : '' ?>">Most Popular</a>
            </div>

            <div class="books-list">
                <?php if (!empty($books)): ?>
                    <?php foreach ($books as $book): ?>
                        <div style="border: 1px solid #ccc; padding: 10px; margin: 10px;">
                            <img src="<?= htmlspecialchars($book['cover_url']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" style="max-width: 200px; max-height: 200px;">
                            <h3><?= htmlspecialchars($book['title']) ?></h3>
                            <p>Adicionado em: <?= htmlspecialchars($book['added_at']) ?></p>
                            <a href="reading.php?book_id=<?= $book['id'] ?>" style="display: inline-block; padding: 5px 10px; background-color: #007bff; color: #fff; text-decoration: none;">Read</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum livro encontrado para a categoria selecionada.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'footer.html'; ?>
</body>
</html>

