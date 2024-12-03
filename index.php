<?php
include_once("db_connection.php");

session_start(); 

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Função para obter livros de acordo com a categoria
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

// Verificar categoria selecionada
$category = isset($_GET['category']) ? $_GET['category'] : "New Books";
$books = getBooks($category);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=lock" />
  <link rel="stylesheet" href="headers.css"> 
   <link rel="stylesheet" href="Style.css">
    
    <link rel="stylesheet" href="index.css">
    <title>Exemplo com iframe</title>
    <style>
    </style>
</head>

<body>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id']): 
        
       include_once("index_header.html")?>
       <!-- Header com Login -->
    <?php else: 
        
        include_once("sem_login.html")?>
        <!-- Header sem Login -->
        
    <?php endif; ?>

    <div class="background-container">
        <!-- Removido o cabeçalho -->

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
                                                    <?php
                                                    // Verificação do tipo de acesso ao livro
                                                    if ($book['access_level'] == 0): ?>
                                                        <!-- Livro Público (acesso 0) -->
                                                        <a href="reading.php?book_id=<?= $book['id'] ?>" class="btn">Read</a>
                                                    <?php elseif ($book['access_level'] == 1): ?>
                                                        <!-- Livro Privado (acesso 1) -->
                                                        <!-- Usuário Premium ou Gratuito pode ver Preview -->
                                                        <a href="preview.php?book_id=<?= $book['id'] ?>" class="btn">
                                                            <span class="material-symbols-outlined">lock</span>
                                                            Preview
                                                        </a>
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