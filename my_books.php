<?php
// Inclui a conexão com o banco de dados
include_once 'db_connection.php';
// Definindo o user_id estaticamente para fins de teste
$user_id = 9;

// Consulta para buscar todos os progressos de leitura do usuário
$sql_progress = "SELECT rp.book_id, rp.current_page, rp.total_pages 
                 FROM reading_progress rp
                 WHERE rp.user_id = ?";
$stmt = $conn->prepare($sql_progress);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_progress = $stmt->get_result();

// Verificação: se não houver registros de progresso
if ($result_progress->num_rows == 0) {
    echo "Nenhum progresso encontrado para o usuário ID: " . $user_id;
}

// Armazena os progressos em um array
$progresses = [];
while ($row = $result_progress->fetch_assoc()) {
    $progresses[] = $row;
}

// Agora, vamos buscar os detalhes dos livros com base nos progressos
$books = [];
if (!empty($progresses)) {
    foreach ($progresses as $progress) {
        $book_id = $progress['book_id'];

        $sql_book = "SELECT b.id, b.title, b.author, b.cover_url 
                     FROM books b 
                     WHERE b.id = ?";
        $stmt = $conn->prepare($sql_book);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result_book = $stmt->get_result();

        // Verificação dos livros
        if ($result_book->num_rows > 0) {
            $book = $result_book->fetch_assoc();
            // Adiciona os detalhes do livro junto com o progresso
            $book['current_page'] = $progress['current_page'];
            $book['total_pages'] = $progress['total_pages'];
            $books[] = $book;
        } else {
            echo "Livro não encontrado para o book_id: " . $book_id . "<br>";
        }
    }
}

// Se não houver livros, exibe uma mensagem de erro
if (empty($books)) {
    echo "Você não tem livros registrados ou com progresso.";
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
    <link rel="stylesheet" href="Styles/my_books.css">
    <title>My Books - Storytails</title>
    <style>
        /* Estilizando a barra de progresso com cor personalizada */
        .progress-bar {
            background-color: #E1700F !important; /* Cor personalizada */
            color: white; /* Cor do texto dentro da barra */
            font-weight: bold; /* Deixa o texto em negrito */
            text-align: center; /* Centraliza o texto */
            height: 30px; /* Aumenta a altura da barra de progresso */
            line-height: 30px; /* Centraliza o texto verticalmente */
        }
    </style>
</head>
<body>
<?php include_once 'header_choose.php'; ?>

<div class="container mt-3">
    <ul class="nav nav-tabs justify-content-center">
        <li class="nav-item">
            <a class="nav-link" href="edit_profile.php">Edit Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="my_books.php">My Books</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="favorite_books.php">Favourite Books</a>
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
    <h2 class="text-center">My Books</h2>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Progress</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($books)): ?>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td>
                            <?php if (!empty($book['cover_url'])): ?>
                                <img src="<?php echo htmlspecialchars($book['cover_url']); ?>" alt="Book Cover" class="book-cover" style="width: 60px; height: 90px; object-fit: cover;">
                            <?php else: ?>
                                <img src="default_cover.jpg" alt="No Cover" class="book-cover" style="width: 60px; height: 90px; object-fit: cover;">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: <?php echo ($book['current_page'] / $book['total_pages']) * 100; ?>%;" 
                                     aria-valuenow="<?php echo $book['current_page']; ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="<?php echo $book['total_pages']; ?>">
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="reading.php?book_id=<?php echo urlencode($book['id']); ?>" class="btn btn-warning">Read Now</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Você não tem livros lidos ou registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>