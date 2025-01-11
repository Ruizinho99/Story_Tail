<?php
include_once("db_connection.php");

// Verificar se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Verificar se os campos obrigatórios estão preenchidos
    if (empty($first_name) || empty($last_name) || empty($email) || empty($senha)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos os campos são obrigatórios!']);
        exit;
    }

    // Verificar se o email existe no banco de dados
    $query = "SELECT id, first_name, last_name, senha FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'O email fornecido não está registrado.']);
        exit;
    }

    // Obter os dados do usuário
    $stmt->bind_result($user_id, $db_first_name, $db_last_name, $db_password);
    $stmt->fetch();

    // Verificar se os outros campos correspondem ao usuário na base de dados
    if ($first_name !== $db_first_name || $last_name !== $db_last_name || !password_verify($senha, $db_password)) {
        echo json_encode(['status' => 'error', 'message' => 'Os dados fornecidos estão incorretos.']);
        exit;
    }

    // Todos os dados estão corretos, inserir a solicitação para o plano premium
    $subject = "Plano Premium";
    $message = "O usuário está solicitando a alteração para o plano premium.";

    $insert_query = "INSERT INTO request (user_id, subject, message) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("iss", $user_id, $subject, $message);

    if ($insert_stmt->execute()) {
        // Se a solicitação for inserida com sucesso, enviar uma resposta de sucesso
        echo json_encode(['status' => 'success', 'message' => 'Your request is being reviewed.']);
    } else {
        // Caso ocorra um erro ao inserir a solicitação
        echo json_encode(['status' => 'error', 'message' => 'There was an error while submitting your request. Please try again later.']);
    }

    // Fechar a declaração
    $stmt->close();
    $insert_stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido!']);
}
?>
