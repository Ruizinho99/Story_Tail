<?php
include 'db_connection.php';
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Função para obter livros com base nos filtros de pesquisa
function getBooksWithFilters($searchTerm = "", $accessLevel = "", $category = "")
{
    global $conn;

    // Filtro de busca SQL
    $sql = "SELECT id, title, cover_url, access_level FROM books WHERE is_active = 1";

    // Adiciona condições com base nos filtros passados
    if ($searchTerm != "") {
        $sql .= " AND title LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
    }
    if ($accessLevel != "") {
        $sql .= " AND access_level = " . intval($accessLevel);
    }
    if ($category != "") {
        $sql .= " AND category = '" . $conn->real_escape_string($category) . "'";
    }

    // Ordena os resultados
    $sql .= " ORDER BY added_at DESC"; // Pode mudar a ordenação conforme necessário

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

// Verifica os filtros passados
$searchTerm = isset($_GET['search']) ? $_GET['search'] : "";
$accessLevel = isset($_GET['access_level']) ? $_GET['access_level'] : "";
$category = isset($_GET['category']) ? $_GET['category'] : "";

// Obter os livros com base nos filtros
$books = getBooksWithFilters($searchTerm, $accessLevel, $category);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=lock" />
    <title>Pesquisa de Livros</title>
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
            justify-content: flex-start; /* Alinha o conteúdo à esquerda */
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #d86c07;
        }

        .material-symbols-outlined {
            margin-right: 8px; /* Espaçamento entre o ícone e o texto */
            font-size: 18px; /* Ajuste do tamanho do ícone */
        }

        .btn[disabled] {
            background-color: #ccc;
        }

        /* Filtro à esquerda */
        .filters {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .filters .form-group {
            margin-bottom: 15px;
        }

        .filters label {
            font-weight: bold;
        }

        .filters select,
        .filters input {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="background-container">
        <?php include 'sl_header.html'; ?>

        <section class="search-section">
            <div class="search-container">
                <h2>Pesquisar Livros</h2>
                <div class="row">
                    <!-- Filtros à esquerda -->
                    <div class="col-md-3">
                        <div class="filters">
                            <form method="GET" action="all_books.php">
                                <div class="form-group">
                                    <label for="search">Pesquisar por título:</label>
                                    <input type="text" id="search" name="search" class="form-control" value="<?= htmlspecialchars($searchTerm) ?>">
                                </div>

                                <div class="form-group">
                                    <label for="access_level">Nível de Acesso:</label>
                                    <select id="access_level" name="access_level" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="0" <?= $accessLevel == "0" ? "selected" : "" ?>>Público</option>
                                        <option value="1" <?= $accessLevel == "1" ? "selected" : "" ?>>Privado</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="category">Categoria:</label>
                                    <select id="category" name="category" class="form-control">
                                        <option value="">Todas</option>
                                        <option value="Fiction" <?= $category == "Fiction" ? "selected" : "" ?>>Ficção</option>
                                        <option value="Non-fiction" <?= $category == "Non-fiction" ? "selected" : "" ?>>Não-ficção</option>
                                        <!-- Adicione outras categorias conforme necessário -->
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </form>
                        </div>
                    </div>

                    <!-- Livros à direita -->
                    <div class="col-md-9">
                        <div class="row row-cols-1 row-cols-md-4 g-3">
                            <?php if (!empty($books)): ?>
                                <?php foreach ($books as $book): ?>
                                    <div class="col d-flex justify-content-center">
                                        <div class="card">
                                            <img src="<?= htmlspecialchars($book['cover_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>">

                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>

                                                <div class="d-flex justify-content-center">
                                                    <?php
                                                    if ($book['access_level'] == 0): ?>
                                                        <a href="reading.php?book_id=<?= $book['id'] ?>" class="btn">Read</a>
                                                    <?php elseif ($book['access_level'] == 1): ?>
                                                        <?php if ($user['user_type_id'] == 1): ?>
                                                            <a href="reading.php?book_id=<?= $book['id'] ?>" class="btn">Read</a>
                                                        <?php else: ?>
                                                            <a href="preview.php?book_id=<?= $book['id'] ?>" class="btn">
                                                                <span class="material-symbols-outlined">lock</span> Preview
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>Nenhum livro encontrado.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'footer.html'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
