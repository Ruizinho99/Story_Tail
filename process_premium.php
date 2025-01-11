<?php
include_once('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $request_id = $_POST['request_id'] ?? '';

    // Verificar se a ação e o ID da solicitação foram fornecidos
    if (empty($action) || empty($request_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Dados incompletos.']);
        exit;
    }

    if ($action === 'accept') {
        // Aceitar o pedido e atualizar o user_type_id para 3 (Premium)
        $sql = "SELECT user_id FROM request WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            echo json_encode(['status' => 'error', 'message' => 'Solicitação não encontrada.']);
            exit;
        }

        // Obter o user_id da solicitação
        $stmt->bind_result($user_id);
        $stmt->fetch();

        // Atualizar o user_type_id para 3 (Premium)
        $update_sql = "UPDATE users SET user_type_id = 3 WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $user_id);
        if ($update_stmt->execute()) {
            // Remover a solicitação da tabela request
            $delete_sql = "DELETE FROM request WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $request_id);
            $delete_stmt->execute();

            echo json_encode(['status' => 'success', 'message' => 'Solicitação aceita e plano alterado para Premium.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar o plano do usuário.']);
        }

        $update_stmt->close();
        $delete_stmt->close();

    } elseif ($action === 'reject') {
        // Rejeitar o pedido e remover o request da tabela
        $delete_sql = "DELETE FROM request WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $request_id);
        if ($delete_stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Solicitação rejeitada.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao rejeitar a solicitação.']);
        }
        $delete_stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ação inválida.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido!']);
}

$conn->close();
?>
