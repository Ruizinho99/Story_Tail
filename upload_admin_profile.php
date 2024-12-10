<?php
// Inclui a conexão com o banco de dados
include_once 'db_connection.php';

// Inicia a sessão para verificar se o usuário está logado
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para editar o perfil.");
}

$user_id = $_SESSION['user_id'];

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $userName = $_POST['username'];

    // Verifica se todos os campos obrigatórios foram preenchidos
    if (!empty($firstName) && !empty($lastName) && !empty($email) && !empty($userName)) {
        // Atualiza o perfil no banco de dados
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, user_name = ? WHERE id = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind dos parâmetros para evitar SQL injection
            $stmt->bind_param("ssssi", $firstName, $lastName, $email, $userName, $user_id);

            // Executa a query para atualizar os dados do usuário
            if ($stmt->execute()) {
                // Verifica se uma nova imagem foi enviada
                if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
                    // Defina o diretório de upload
                    $uploadDir = 'uploads/';
                    $imageFileName = basename($_FILES['profileImage']['name']);
                    $targetFile = $uploadDir . $imageFileName;

                    // Mover o arquivo para o diretório de uploads
                    if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $targetFile)) {
                        // Atualiza a foto do perfil no banco de dados
                        $updateImageSql = "UPDATE users SET user_photo_url = ? WHERE id = ?";
                        if ($stmt = $conn->prepare($updateImageSql)) {
                            $stmt->bind_param("si", $imageFileName, $user_id);
                            if (!$stmt->execute()) {
                                // Captura erro de execução
                                $_SESSION['statusMessage'] = "Erro ao atualizar a imagem no banco de dados: " . $stmt->error;
                            }
                        } else {
                            $_SESSION['statusMessage'] = "Erro ao preparar a consulta SQL para atualizar a imagem.";
                        }
                    } else {
                        $_SESSION['statusMessage'] = "Erro ao mover o arquivo para o diretório de uploads.";
                    }
                }
                // Mensagem de sucesso
                $_SESSION['statusMessage'] = "Perfil atualizado com sucesso!";
            } else {
                // Caso de erro na execução da atualização do perfil
                $_SESSION['statusMessage'] = "Erro ao atualizar o perfil: " . $stmt->error;
            }

            // Fechar o statement
            $stmt->close();
        } else {
            // Caso a consulta SQL não seja preparada corretamente
            $_SESSION['statusMessage'] = "Erro na preparação da consulta SQL.";
        }
    } else {
        $_SESSION['statusMessage'] = "Todos os campos são obrigatórios.";
    }

    // Redireciona para a página de edição do perfil
    header("Location: edit_admin_profile.php");
    exit();
}
?>
