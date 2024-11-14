<?php
include 'db_connection.php';
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Função para verificar o acesso ao livro
function canAccessBook($book, $user)
{
    // Verifica se o livro tem acesso restrito (privado)
    if ($book['access_level'] === 'private') {
        // Verifica se o usuário é premium
        return $user['user_type_id'] === 1; // Considerando 1 como ID de usuários premium
    }
    // Se o acesso do livro for público, todos podem acessar
    return true;
}

function getBooks($category)
{
    global $conn;

    // SQL diferente baseado na "categoria"
    if ($category == 'New Books') {
        $sql = "SELECT id, title, cover_url, access_level FROM books WHERE is_active = 1 ORDER BY added_at DESC"; // Mais recentes primeiro
    } else {
        $sql = "SELECT id, title, cover_url, access_level FROM books WHERE is_active = 1 ORDER BY RAND()"; // Ordem aleatória
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

// Supondo que você tenha uma variável $user com os dados do usuário logado
// Exemplo: $user = ['user_type_id' => 1]; 1 para premium, 2 para não premium
$user = ['user_type_id' => 1];  // Exemplo de um usuário premium
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Para o ícone de cadeado -->
    <title>Exemplo com iframe</title>
    <style>
        .card {
            width: 200px;
            height: 300px;
            border-radius: 10px;
            border: 1px solid #ccc;
            overflow: hidden;
            position: relative;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-body {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px;
            background: rgba(0, 0, 0, 0.6); /* Fundo escuro e semitransparente */
            color: white;
            text-align: center;
            z-index: 2;
        }

        .card-title {
            font-size: 1rem;
            margin: 0;
        }

        .btn {
            background-color: #E1700F;
            color: white;
            border: none;
        }

        .btn[disabled] {
            background-color: #ccc;
        }

        .fa-lock {
            color: white;
        }
    </style>
</head>

<body>
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

    <!-- Seção New Books -->
    <section class="new-books-section">
        <?php
        echo '<link rel="stylesheet" type="text/css" href="styles/new_books.css">';

        $category = isset($_GET['category']) ? $_GET['category'] : "New Books";
        $books = getBooks($category);
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
                        <div class="row row-cols-1 row-cols-md-4 g-3">
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

                                                <div class="d-flex justify-content-center">
                                                    <?php if ($book['access_level'] == 'public'): ?>
                                                        <a href="reading.php?book_id=<?= $book['id'] ?>" class="btn">Read</a>
                                                        <a href="preview.php?book_id=<?= $book['id'] ?>" class="btn">Preview</a>
                                                    <?php else: ?>
                                                        <button class="btn" disabled>
                                                            <i class="fa fa-lock"></i> Preview
                                                        </button>
                                                        <a href="reading.php?book_id=<?= $book['id'] ?>" class="btn">Read</a>
                                                    <?php endif; ?>
                                                </div>
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
