<?php
include_once('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = isset($_POST['id']) ? intval($_POST['id']) : null;
    $userType = isset($_POST['type']) ? intval($_POST['type']) : null;

    if ($userId && in_array($userType, [1, 2, 3])) { // Incluindo o tipo 3 para Premium
        $sql = "UPDATE users SET user_type_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userType, $userId);

        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Tipo de usuário atualizado com sucesso!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Erro ao atualizar o tipo de usuário.'
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Dados inválidos fornecidos.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método de requisição inválido.'
    ]);
}

$conn->close();
?>
