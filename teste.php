<?php
// Incluir a conexão com o banco de dados
include 'db_connection.php';

// Verificar se o formulário foi enviado e se a imagem foi carregada
if (isset($_FILES['user_photo']) && $_FILES['user_photo']['error'] == 0) {

    // Definir o diretório de upload
    $upload_dir = 'images/';
    
    // Obter o nome e extensão do arquivo
    $file_name = $_FILES['user_photo']['name'];
    $file_tmp_name = $_FILES['user_photo']['tmp_name'];
    $file_size = $_FILES['user_photo']['size'];
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    
    // Definir um nome único para o arquivo
    $new_file_name = uniqid() . '.' . $file_extension;
    
    // Definir o caminho completo para o upload
    $upload_file = $upload_dir . $new_file_name;

    // Verificar se a extensão do arquivo é permitida
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array(strtolower($file_extension), $allowed_extensions)) {
        
        // Mover o arquivo para o diretório de upload
        if (move_uploaded_file($file_tmp_name, $upload_file)) {
            
            // Preparar a URL do arquivo para salvar no banco de dados
            $file_url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $upload_file;

            // Inserir a URL da imagem na base de dados
            $sql = "UPDATE users SET user_photo_url = ? WHERE user_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $file_url, $user_name); // Alterar '$user_name' conforme necessário
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Imagem enviada com sucesso e URL adicionada à base de dados!";
            } else {
                echo "Erro ao atualizar a base de dados!";
            }

            // Fechar a declaração
            $stmt->close();
        } else {
            echo "Erro ao mover o arquivo para o diretório.";
        }
    } else {
        echo "Extensão de arquivo não permitida. Apenas JPG, JPEG, PNG, e GIF são permitidos.";
    }
}

// Fechar a conexão
$conn->close();
?>

<!-- Formulário HTML para enviar a imagem -->
<form action="teste.php" method="POST" enctype="multipart/form-data">
    <label for="user_photo">Escolha uma imagem:</label>
    <input type="file" name="user_photo" id="user_photo" required>
    <input type="submit" value="Enviar">
</form>
