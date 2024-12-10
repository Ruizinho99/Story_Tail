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
    <style>
        /* Estilo do fundo escuro para a página */
        body {
            background-color: #2c2c2c;
            color: white;
        }

        /* Estilo dos cards com fundo mais escuro */
        .card {
            max-width: 540px;
            height: 300px;
            cursor: pointer;
            text-decoration: none;
            color: white;
            background-color: #3a3a3a; /* Fundo mais escuro */
            border: none; /* Remove a borda padrão */
        }

        .card:hover {
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            text-decoration: none;
            color: gray;
        }

        .card-body {
            position: relative;
        }

        .card-text {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            color: white
        }

        /* Placeholder para imagem com proporção fixa */
        .image-placeholder {
            width: 100%;
            max-width: 200px;
            aspect-ratio: 2 / 3;
            background-color: #1e1e1e;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border: 2px solid white;
            border-radius: 4px;
        }

        .image-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <?php include_once 'admin_sidebar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 g-3">
                    <?php if (!empty($books)): ?>
                        <?php foreach ($books as $index => $book): ?>
                            <?php if ($index >= 8) break; ?>
                            <div class="col">
                                <a href="edit_books.php?book_id=<?= $book['id'] ?>" class="card-link" style="text-decoration: none; color: black">
                                    <div class="card mb-3">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-md-4">
                                                <!-- Placeholder para a imagem -->
                                                <div class="image-placeholder">
                                                    <img src="<?= htmlspecialchars($book['cover_url']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                                                    <p class="card-text"><?= htmlspecialchars($book['description'] ?? 'Descrição não disponível') ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
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
