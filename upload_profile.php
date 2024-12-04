<?php
include_once("user_logged_in.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado.");
}

$user_id = $_SESSION['user_id'];
include_once 'db_connection.php';

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$_SESSION['statusMessage'] = ""; // Inicializa a mensagem de status

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);

    // Verifica e cria a pasta "uploads" se não existir
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $sql = "UPDATE users SET 
                first_name='$firstName', 
                last_name='$lastName', 
                email='$email', 
                user_name='$username' 
            WHERE id='$user_id'";
    
    $imageUpdated = false;

    // Lida com o upload da imagem
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        $imagePath = $uploadDir . basename($_FILES['profileImage']['name']);
        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath)) {
            $imagePathEscaped = $conn->real_escape_string($imagePath);
            $sql = "UPDATE users SET 
                        first_name='$firstName', 
                        last_name='$lastName', 
                        email='$email', 
                        user_name='$username', 
                        user_photo_url='$imagePathEscaped' 
                    WHERE id='$user_id'";
            $imageUpdated = true;
        } else {
            $_SESSION['statusMessage'] = "Erro ao mover o arquivo.";
        }
    }

    if ($conn->query($sql) === TRUE) {
        $_SESSION['statusMessage'] = $imageUpdated 
            ? "Imagem e perfil atualizados com sucesso." 
            : "Perfil atualizado com sucesso.";
    } else {
        $_SESSION['statusMessage'] = "Erro ao atualizar: " . $conn->error;
    }
}

$conn->close();
header("Location: edit_profile.php");
exit();
?>
