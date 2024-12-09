<?php
// Inclui a conexão com o banco de dados
include_once 'db_connection.php';

// Busca todos os livros
$query = "SELECT * FROM books";
$result = $conn->query($query);

// Verifica se há livros na base de dados
if ($result->num_rows > 0) {
    // Exibe cada livro como um card
    while ($book = $result->fetch_assoc()) {
        ?>
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="<?= htmlspecialchars($book['cover_url']) ?>" class="card-img-top" alt="Book Cover">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($book['author']) ?></p>
                    <a href="edit_books.php?book_id=<?= $book['id'] ?>" class="btn btn-primary">Edit</a>
                    <a href="delete_book.php?book_id=<?= $book['id'] ?>" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p>No books found.</p>";
}
?>
