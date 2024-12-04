<?php
// Inclui lógica para obter o ID do usuário logado
include_once("user_logged_in.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para enviar uma mensagem.");
}

$user_id = $_SESSION['user_id']; // Obtém o ID do usuário logado

// Inclui a conexão com o banco de dados
include_once 'db_connection.php'; // Adicionado ponto e vírgula aqui

// Verifica se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Escapa os dados para evitar SQL Injection
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);

    // Agora, a consulta está inserindo na tabela 'request'
    $sql = "INSERT INTO request (user_id, subject, message) VALUES ('$user_id', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Mensagem enviada com sucesso!";
        header("Location: help.php?status=success"); // Redireciona para 'help.php' com status de sucesso
        exit();
    } else {
        echo "Erro ao enviar a mensagem: " . $conn->error;
    }
}

$conn->close(); // Fecha a conexão com o banco de dados
?>
