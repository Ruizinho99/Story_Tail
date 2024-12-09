<?php
// Inclui a conexão com o banco de dados
include_once 'db_connection.php';

// Consulta os livros na base de dados
$query = "SELECT * FROM books";
$result = $conn->query($query);

// Armazena os livros encontrados em um array
$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/books.css">
</head>
<body>
    <?php include_once 'admin_sidebar.php'; ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10">
                <!-- Adicionando classes responsivas -->
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
                    <?php if (!empty($books)): ?>
                        <?php foreach ($books as $index => $book): ?>
                            <!-- Limite de exibição para 8 cartões -->
                            <?php if ($index >= 8) break; ?>
                            <div class="col d-flex justify-content-center">
                                <div class="card">
                                    <!-- Imagem do cartão ajustada -->
                                    <img src="<?= htmlspecialchars($book['cover_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>">

                                    <!-- Corpo do cartão com fundo preto semitransparente -->
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>

                                        <!-- Botão Edit abaixo do livro -->
                                        <div class="d-flex justify-content-center mt-2">
                                            <a href="edit_books.php?book_id=<?= $book['id'] ?>" class="btn" style="background-color:#007BFF; color:white;">Edit</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">Nenhum livro encontrado.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
