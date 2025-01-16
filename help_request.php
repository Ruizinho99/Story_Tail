<?php
// Incluir a conexão com o banco de dados
include('db_connection.php');
 include_once 'header_choose.php'
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Help Requests</title>
    <!-- Incluir o link para o Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e1e;
        }

        .message {
            max-height: 100px;
            overflow-y: auto;
            white-space: pre-wrap; /* Permite que o texto longo quebre linha */
        }

        .message::-webkit-scrollbar {
            width: 6px;
        }

        .message::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        .message::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }

        .card-body {
            padding: 15px;
        }

        .card {
            margin: 15px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .user-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .card-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Estilo personalizado para o botão */
        .btn-custom {
            background-color: #E1700F;
            border-color: #E1700F;
        }

        .btn-custom:hover {
            background-color: #D2600C;
            border-color: #D2600C;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            // Consulta SQL para obter os requests dos usuários
            $sql = "
                SELECT 
                    r.id AS request_id,
                    r.subject,
                    r.message,
                    r.created_at,
                    u.first_name,
                    u.last_name,
                    u.email,
                    u.user_photo_url
                FROM request r
                JOIN users u ON r.user_id = u.id
                WHERE r.subject != 'Plano Premium'
                ORDER BY r.created_at DESC
            ";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Exibir os resultados
                while($row = $result->fetch_assoc()) {
                    // Definir o caminho da imagem do usuário, verificando se a imagem existe
                    $user_photo_path = 'uploads/' . $row['user_photo_url']; // Pasta onde as imagens estão armazenadas

                    // Verificar se a imagem existe antes de exibir
                    if (file_exists($user_photo_path) && !empty($row['user_photo_url'])) {
                        $user_photo = $user_photo_path;
                    } else {
                        $user_photo = 'uploads/default-profile.png'; // Caso não haja imagem, exibe uma imagem padrão
                    }

                    echo "<div class='col'>
                            <div class='card'>
                                <div class='d-flex align-items-center p-3'>
                                    <img src='" . $user_photo . "' class='user-photo' alt='User Image'>
                                    <div class='ms-3 text-start'>
                                        <h5 class='card-title mb-1'>" . $row['first_name'] . " " . $row['last_name'] . "</h5>
                                        <p class='card-text mb-0'>Email: " . $row['email'] . "</p>
                                        <p><strong>Criado em:</strong> " . $row['created_at'] . "</p>
                                    </div>
                                </div>
                                <div class='card-body'>
                                    <h6><strong>Assunto:</strong> " . $row['subject'] . "</h6>
                                    <div class='message border p-2 rounded' style='background-color: #f9f9f9;'>
                                        " . nl2br($row['message']) . "
                                    </div>  
                                    <!-- Botão para responder -->
                                    <a href='response.php?request_id=" . $row['request_id'] . "' style='color: white' class='btn btn-custom mt-3'>Responder</a>
                                </div>
                            </div>
                        </div>";
                }
            } else {
                echo "<p>Não há requests.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Incluir o script do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
