<?php
// Inclui a conexão com o banco de dados
include_once 'db_connection.php';
include_once("user_logged_in.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para acessar seus livros.");
}

$user_id = $_SESSION['user_id'];

// Consulta os livros do usuário
$sql = "SELECT b.cover_image, b.title, ub.completion FROM user_books ub
        JOIN books b ON ub.book_id = b.id
        WHERE ub.user_id = '$user_id'";
$result = $conn->query($sql);

// Armazena os livros em um array
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
    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="Styles/headers.css">
    <link rel="stylesheet" href="Styles/my_books.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=lock" />
    <title>My Books - Storytails</title>
</head>
<body>
<?php include_once 'header_choose.php'; ?>

<!-- Navigation Tabs -->
<div class="container mt-3">
    <ul class="nav nav-tabs justify-content-center">
        <li class="nav-item">
            <a class="nav-link" href="edit_profile.php">Edit Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#">My Books</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="favorite_books.php">Favorite Books</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="change_password.php">Change Password</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="plan.php">Plan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="help.php">Help</a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<section class="container mt-5">
    <h2 class="text-center">My Books</h2>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Completion</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td>
                            <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" alt="Book Cover" class="book-cover">
                        </td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar"
                                     style="width: <?php echo intval($book['completion']); ?>%;"
                                     aria-valuenow="<?php echo intval($book['completion']); ?>"
                                     aria-valuemin="0" aria-valuemax="100">
                                    <?php echo intval($book['completion']); ?>%
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="read_book.php?book_id=<?php echo urlencode($book['id']); ?>" class="btn btn-warning">Read Now</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">You have no books yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<?php include 'footer.html'; ?>
</body>
</html>
