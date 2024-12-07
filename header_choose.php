<?php
include_once("db_connection.php");

// Inicia a sessão apenas se não estiver já ativa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se a conexão ainda está aberta
if (!$conn || $conn->connect_error) {
    die("Conexão perdida ou não inicializada");
}

// Verifica se o usuário está logado
if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    // Obtém os dados do usuário do banco de dados
    $user_id = $_SESSION['user_id'];
    $query = "SELECT user_type_id FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $user_type_id = $user['user_type_id'];

            // Seleciona o header baseado no user_type_id
            if ($user_type_id == 1) {
                include_once("header_admin.html"); // Header para admin
            } else {
                include_once("header_user.html"); // Header para user free e user premium
            }
        } else {
            // Caso o usuário não seja encontrado no banco de dados
            include_once("header_s_login.html");
        }
        $stmt->close();
    } else {
        die("Erro ao preparar a consulta: " . $conn->error);
    }
} else {
    // Header para usuários não autenticados
    include_once("header_s_login.html");
}
?>
