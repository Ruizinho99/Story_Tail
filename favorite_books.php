<?php
// Inclui a conexão com o banco de dados
include_once 'db_connection.php';
include_once 'user_logged_in.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para acessar seus livros favoritos.");
}

$user_id = $_SESSION['user_id'];

// Consulta os livros favoritos do usuário
$sql = "SELECT b.id, b.cover_url, b.title, b.author
        FROM favourite_books fb
        JOIN books b ON fb.book_id = b.id
        WHERE fb.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Armazena os livros favoritos em um array
$fav_books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fav_books[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/headers.css">
    <link rel="stylesheet" href="Styles/favorite_books.css">
    <title>Favorite Books - Storytails</title>
</head>

<body>
    <?php include_once 'header_choose.php'; ?>

    <div class="container mt-3">
        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="edit_profile.php">Edit Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="my_books.php">My Books</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="favorite_books.php">Favourite Books</a>
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

    <section class="container mt-5">
        <h2 class="text-center">Favorite Books</h2>
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($fav_books)): ?>
                    <?php foreach ($fav_books as $book): ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlspecialchars($book['cover_url']); ?>" alt="Book Cover" class="book-cover" style="width: 50px; height: auto;">
                            </td>
                            <td><?php echo htmlspecialchars($book['title']); ?></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td>
                                <a href="reading.php?book_id=<?php echo urlencode($book['id']); ?>" class="btn btn-warning">Read Now</a>
                                <a href="remove_favorite.php?book_id=<?php echo urlencode($book['id']); ?>"
                                    class="btn btn-danger"
                                    style="background-color: #E1700F; border-color: #E1700F;">X</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">You have no favorite books yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>

        </table>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'footer.html'; ?>
</body>

</html>