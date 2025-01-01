<?php
session_start();
include_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['book_id']); // ID do livro recebido via POST

// Verificar se o livro já é favorito
$sql_check = "SELECT * FROM favourite_books WHERE user_id = ? AND book_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $user_id, $book_id);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    // Já é favorito, então remover
    $sql_delete = "DELETE FROM favourite_books WHERE user_id = ? AND book_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ii", $user_id, $book_id);
    $stmt_delete->execute();
    echo json_encode(['status' => 'removed']);
} else {
    // Não é favorito, então adicionar
    $sql_insert = "INSERT INTO favourite_books (user_id, book_id) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ii", $user_id, $book_id);
    $stmt_insert->execute();
    echo json_encode(['status' => 'added']);
}
