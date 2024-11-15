<?php
// Defina as informações de conexão
$host = 'localhost'; // ou o IP do servidor MySQL
$username = 'root';  // Nome de usuário do MySQL
$password = '';      // Senha do MySQL
$dbname = 'LP';      // Nome da base de dados que você quer usar

// Criar a conexão com o MySQL
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

session_start(); // Certifique-se de que a sessão está iniciada aqui também

$isLoggedIn = isset($_SESSION['user']); // Verifica se o usuário está logado

if ($isLoggedIn) {
    include 'header.html'; // Inclui o cabeçalho de usuários logados
} else {
    include 'sl_header.html'; // Inclui o cabeçalho de usuários não logados
}


if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Função para verificar se o livro é acessível
function canAccessBook($book, $user)
{
    // Se o livro é privado (1) e o usuário não é premium
    if ($book['access_level'] == 1) {
        // Verifica se o usuário é premium
        if ($user['user_type_id'] == 1) {
            return true; // Usuário premium pode acessar
        } else {
            return false; // Usuário não premium não pode acessar
        }
    }
    return true; // Se for público (0), todos podem acessar
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

// Verifica se o usuário está logado
$isLoggedIn = isset($_SESSION['user']);
// Se estiver logado, utiliza a informação do usuário da sessão; se não, define como usuário gratuito
$user = $isLoggedIn ? $_SESSION['user'] : ['user_type_id' => 2]; // Exemplo de um usuário gratuito (Free)

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=lock" />
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
            background: rgba(0, 0, 0, 0.6);
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            font-size: 14px;
        }

        .btn[disabled] {
            background-color: #ccc;
        }

        .material-symbols-outlined {
            margin-left: 8px; /* Espaço entre o texto e o ícone */
            font-size: 20px; /* Ajuste do tamanho do ícone */
        }
    </style>
</head>

<body>
    <div class="background-container">
        <?php 
            // Inclui o cabeçalho apropriado com base no estado de login do usuário
            if ($isLoggedIn) {
                include 'header.html';
            } else {
                include 'sl_header.html';
            }
        ?>

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
                                                    // Verificação do tipo de acesso ao livro e tipo de usuário
                                                    if ($book['access_level'] == 0): ?>
                                                        <!-- Livro Público (acesso 0) -->
                                                        <a href="reading.php?book_id=<?= $book['id'] ?>" class="btn">Read</a>
                                                    <?php elseif ($book['access_level'] == 1): ?>
                                                        <!-- Livro Privado (acesso 1) -->
                                                        <?php if ($user['user_type_id'] == 1): ?>
                                                            <!-- Usuário Premium pode ler -->
                                                            <a href="reading.php?book_id=<?= $book['id'] ?>" class="btn">Read</a>
                                                        <?php else: ?>
                                                            <!-- Usuário Gratuito verá Preview com ícone de cadeado dentro do botão -->
                                                            <a href="preview.php?book_id=<?= $book['id'] ?>" class="btn">
                                                                <span class="material-symbols-outlined">lock</span>
                                                                Preview
                                                            </a>
                                                        <?php endif; ?>
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