<?php
// Conectar ao banco de dados
include_once("db_connection.php");

// Verificar se o parâmetro author_id foi passado
if (!isset($_GET['author_id']) || empty($_GET['author_id'])) {
    die("Erro: Autor não especificado.");
}

$author_id = intval($_GET['author_id']); // Sanitizar entrada

// Consulta SQL para obter informações do autor
$sql = "SELECT first_name, last_name, description, author_photo_url, nationality, amazon FROM authors WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $author_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Author not found.");
}

$author = $result->fetch_assoc();
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
    <title>Author Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php 
    include_once 'header_choose.php'
    ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <!-- Foto do autor -->
            <div class="col-md-3 text-center">
                <img src="<?= !empty($author['author_photo_url']) ? htmlspecialchars($author['author_photo_url']) : 'uploads/default-author.png' ?>" 
                     alt="<?= htmlspecialchars($author['first_name'] . ' ' . $author['last_name']) ?>" 
                     class="img-fluid rounded-circle">
            </div>
            <!-- Nome e descrição -->
            <div class="col-md-8">
                <h1><?= htmlspecialchars($author['first_name'] . ' ' . $author['last_name']) ?></h1>
                <p><strong>Nationality:</strong> <?= htmlspecialchars($author['nationality']) ?></p>
                <p><?= nl2br(htmlspecialchars($author['description'])) ?></p>

                <?php if (!empty($author['amazon'])): ?>
                    <p>
                        Find more about 
                        <strong><?= htmlspecialchars($author['first_name'] . ' ' . $author['last_name']) ?></strong> 
                        <a href="<?= htmlspecialchars($author['amazon']) ?>" target="_blank">here</a>.
                    </p>
                <?php endif; ?>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <?php include 'footer.html'; ?> 
</body>
</html>
