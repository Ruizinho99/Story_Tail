<?php
// validar_login.php

include_once 'db_connection.php'; // Incluir a conexão com o banco de dados

// Variáveis para armazenar mensagens
$successMessage = "";
$errorMessage = "";

// Processar o formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username_or_email']) && isset($_POST['senha'])) {
    $usernameOrEmail = $_POST['username_or_email'];
    $password = $_POST['senha'];

    // Usar prepared statements para evitar SQL Injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = ? OR email = ?");
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail); // Bind dos parâmetros
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // O utilizador existe, verificar a password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['senha'])) {
            // Password correta, redirecionar para a página inicial
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['user_name'];
            $_SESSION['user_type_id'] = $user['user_type_id'];
            header("Location: index.php"); // Redireciona para a homepage
            exit();
        } else {
            // Password incorreta
            $errorMessage = "Username ou password incorreta.";
        }
    } else {
        // Utilizador não encontrado
        $errorMessage = "Username ou password incorreta.";
    }
}
?>
