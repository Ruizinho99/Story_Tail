<?php
include 'db_connection.php';

// Obter dados do POST
$data = json_decode(file_get_contents('php://input'), true);

$book_id = $data['book_id'];
$user_id = $data['user_id'];
$current_page = $data['current_page'];

if ($book_id > 0 && $user_id > 0 && $current_page > 0) {
    // Verificar se já existe um registro para este livro e usuário
    $sql = "SELECT * FROM reading_progress WHERE book_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $book_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Atualizar o progresso
        $sql = "UPDATE reading_progress SET current_page = ? WHERE book_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $current_page, $book_id, $user_id);
        $stmt->execute();
    } else {
        // Inserir um novo registro de progresso
        $sql = "INSERT INTO reading_progress (book_id, user_id, current_page) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $book_id, $user_id, $current_page);
        $stmt->execute();
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
