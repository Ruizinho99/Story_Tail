<?php
include_once("db_connection.php");

// Inicia a sessão apenas se não estiver já ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    // Obtém os dados do usuário do banco de dados
    $user_id = $_SESSION['user_id'];
    $query = "SELECT user_type_id FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_type_id = $user['user_type_id'];

        // Seleciona o header baseado no user_type_id
        if ($user_type_id == 1) {
            include_once("admin_dashboard.php"); // Header para admin
        } else {
            include_once("header_user.php"); // Header para user free e user premium
        }
    } else {
        // Caso o usuário não seja encontrado no banco de dados
        include_once("header_s_login.html");
    }
} else {
    // Header para usuários não autenticados
    include_once("header_s_login.html");
}

// Código para pesquisa e filtros continua daqui
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$authorFilter = isset($_GET['author']) ? $_GET['author'] : '';
$ageGroupFilter = isset($_GET['age_group']) ? $_GET['age_group'] : '';
$yearFilter = isset($_GET['publication_year']) ? $_GET['publication_year'] : '';

// Construir a query dinâmica
$sql = "SELECT * FROM books WHERE is_active = 1";

if ($query) {
    $sql .= " AND title LIKE ?";
}
if ($authorFilter) {
    $sql .= " AND author = ?";
}
if ($ageGroupFilter) {
    $sql .= " AND age_group = ?";
}
if ($yearFilter) {
    $sql .= " AND publication_year = ?";
}

$stmt = $conn->prepare($sql);
$params = [];
if ($query) {
    $params[] = '%' . $query . '%';
}
if ($authorFilter) {
    $params[] = $authorFilter;
}
if ($ageGroupFilter) {
    $params[] = $ageGroupFilter;
}
if ($yearFilter) {
    $params[] = $yearFilter;
}

if ($params) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$books = [];
while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

$authors = $conn->query("SELECT DISTINCT author FROM books WHERE is_active = 1")->fetch_all(MYSQLI_ASSOC);
$ageGroups = $conn->query("SELECT DISTINCT age_group FROM books WHERE is_active = 1")->fetch_all(MYSQLI_ASSOC);
$years = $conn->query("SELECT DISTINCT publication_year FROM books WHERE is_active = 1")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/search_results.css">
    <title>Search Results</title>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <h5>Filters</h5>
                <form method="GET" action="search_results.php">
                    <input type="hidden" name="query" value="<?= htmlspecialchars($query) ?>">
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <select class="form-select" id="author" name="author">
                            <option value="">All</option>
                            <?php foreach ($authors as $author): ?>
                                <option value="<?= htmlspecialchars($author['author']) ?>" <?= $author['author'] == $authorFilter ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($author['author']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="age_group" class="form-label">Age Group</label>
                        <select class="form-select" id="age_group" name="age_group">
                            <option value="">All</option>
                            <?php foreach ($ageGroups as $group): ?>
                                <option value="<?= htmlspecialchars($group['age_group']) ?>" <?= $group['age_group'] == $ageGroupFilter ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($group['age_group']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="publication_year" class="form-label">Publication Year</label>
                        <select class="form-select" id="publication_year" name="publication_year">
                            <option value="">All</option>
                            <?php foreach ($years as $year): ?>
                                <option value="<?= htmlspecialchars($year['publication_year']) ?>" <?= $year['publication_year'] == $yearFilter ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($year['publication_year']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </form>
            </div>
            <div class="col-md-9">
                <h5>Search Results</h5>
                <?php if (!empty($books)): ?>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        <?php foreach ($books as $book): ?>
                            <div class="col">
                                <div class="card">
                                    <img src="<?= htmlspecialchars($book['cover_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                                        <p class="card-text">Author: <?= htmlspecialchars($book['author']) ?></p>
                                        <p class="card-text">Year: <?= htmlspecialchars($book['publication_year']) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No books found matching your search criteria.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'footer.html'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
