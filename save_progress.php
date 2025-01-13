<?php
include 'db_connection.php';
header('Content-Type: application/json');

// Pega os dados enviados via POST
$data = json_decode(file_get_contents('php://input'), true);

$user_id = isset($data['user_id']) ? $data['user_id'] : null;
$book_id = isset($data['book_id']) ? $data['book_id'] : null;
$current_page = isset($data['current_page']) ? $data['current_page'] : null;
$total_pages = isset($data['total_pages']) ? $data['total_pages'] : null;

if (!$user_id || !$book_id || !$current_page || !$total_pages) {
    echo json_encode(['error' => 'Dados inválidos']);
    exit;
}

// Verifica se já existe um registro para esse usuário e livro
$sql = "SELECT * FROM reading_progress WHERE user_id = ? AND book_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $user_id, $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Atualiza o progresso e o total de páginas
    $sql = "UPDATE reading_progress SET current_page = ?, total_pages = ? WHERE user_id = ? AND book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiii', $current_page, $total_pages, $user_id, $book_id);
    $stmt->execute();
    echo json_encode(['message' => 'Progresso atualizado']);
} else {
    // Insere o progresso e o total de páginas pela primeira vez
    $sql = "INSERT INTO reading_progress (user_id, book_id, current_page, total_pages) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiii', $user_id, $book_id, $current_page, $total_pages);
    $stmt->execute();
    echo json_encode(['message' => 'Progresso salvo']);
}
