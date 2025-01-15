<?php
session_start();
include_once("db_connection.php");
header("Content-Type: application/json");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['book_id'], $data['rating']) || !is_numeric($data['rating'])) {
    echo json_encode(["success" => false, "error" => "Invalid input."]);
    exit;
}
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "You must be logged in to rate this book."]);
    exit;
}
$user_id = $_SESSION['user_id'];
$book_id = intval($data['book_id']);
$rating = intval($data['rating']);
if ($rating < 1 || $rating > 5) {
    echo json_encode(["success" => false, "error" => "Rating must be between 1 and 5."]);
    exit;
}
// Verificar se o usuário já avaliou este livro
$sql = "SELECT id FROM ratings WHERE user_id = ? AND book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $book_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // Atualizar avaliação existente
    $sql = "UPDATE ratings SET rating = ?, rating_date = NOW() WHERE user_id = ? AND book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $rating, $user_id, $book_id);
} else {
    // Inserir nova avaliação
    $sql = "INSERT INTO ratings (user_id, book_id, rating, rating_date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $book_id, $rating);
}
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to save rating."]);
}
?>