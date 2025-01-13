<?php
include 'db_connection.php';

// Iniciar a sessão
session_start();

// Obter o número de páginas lidas e o ID do livro via GET
$pages_read = isset($_GET['pages_read']) ? intval($_GET['pages_read']) : 0;
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; // Obtém o user_id da sessão

if ($book_id > 0 && $user_id > 0) {
    // Verifica se já existe um progresso de leitura para o usuário
    $sql_check_progress = "SELECT * FROM reading_progress WHERE user_id = ? AND book_id = ?";
    $stmt_check = $conn->prepare($sql_check_progress);
    $stmt_check->bind_param("ii", $user_id, $book_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Se já existe, pega o progresso
        $progress = $result_check->fetch_assoc();
        $current_pages_read = $progress['pages'] + $pages_read; // Soma as páginas lidas

        // Atualiza o progresso com o novo número de páginas lidas
        $sql_update = "UPDATE reading_progress SET pages = ? WHERE user_id = ? AND book_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("iii", $current_pages_read, $user_id, $book_id);
        $stmt_update->execute();
    } else {
        // Se não existe, cria um novo registro de progresso
        $sql_insert = "INSERT INTO reading_progress (user_id, book_id, pages) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iii", $user_id, $book_id, $pages_read);
        $stmt_insert->execute();
    }

    // Exibe as páginas lidas
    echo "<h3>Páginas lidas: $current_pages_read</h3>";
} else {
    echo "ID de livro ou usuário inválido.";
    exit;
}
?>
