<?php
include_once 'db_connection.php';
include_once 'user_logged_in.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['book_id'])) {
    die("Invalid request.");
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_GET['book_id']);

// Remove o livro dos favoritos
$sql = "DELETE FROM favourite_books WHERE user_id = ? AND book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $book_id);

if ($stmt->execute()) {
    header("Location: favorite_books.php");
    exit;
} else {
    echo "Error removing book.";
}
?>
