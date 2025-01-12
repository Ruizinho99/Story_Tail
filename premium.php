<?php
// Conectar ao banco de dados
include_once('db_connection.php');

// Verificar se o usuário é admin (user_type_id = 1)
session_start();
if ($_SESSION['user_type_id'] != 1) {
    header("Location: index.php"); // Redireciona caso não seja admin
    exit;
}

// Consulta SQL para obter as solicitações de planos
$sql = "
    SELECT 
        r.id AS request_id,
        r.user_id,
        r.subject,
        r.message,
        r.created_at,
        u.first_name,
        u.last_name,
        u.email
    FROM request r
    JOIN users u ON r.user_id = u.id
    WHERE r.subject = 'Plano Premium'
    ORDER BY r.created_at DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Solicitações de Plano Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="Styles/premium.css">
</head>
<body>
    <div class="container py-5">
        <h2>Solicitações de Alteração de Plano</h2>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                // Exibir as solicitações
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='col-12 col-md-6 col-lg-4 mb-4' id='request-" . $row['request_id'] . "'>
                        <div class='card'>
                            <div class='card-body'>
                                <h5 class='card-title'>" . $row['first_name'] . " " . $row['last_name'] . "</h5>
                                <p><strong>Email:</strong> " . $row['email'] . "</p>
                                <p><strong>Assunto:</strong> " . $row['subject'] . "</p>
                                <p><strong>Mensagem:</strong> " . $row['message'] . "</p>
                                <p><strong>Solicitado em:</strong> " . $row['created_at'] . "</p>
                                <button class='btn btn-success accept-btn' data-id='" . $row['request_id'] . "'>Aceitar</button>
                                <button class='btn btn-danger reject-btn' data-id='" . $row['request_id'] . "'>Rejeitar</button>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<p>Não há solicitações de plano.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        // Função para aceitar ou rejeitar um pedido
        $(".accept-btn").click(function() {
        var requestId = $(this).data("id");
        $.ajax({
            url: 'process_premium.php', // Novo arquivo PHP que vai lidar com a aceitação
            type: 'POST',
            data: {
                action: 'accept',
                request_id: requestId
            },
            success: function(response) {
                // Garantir que o retorno seja um JSON
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    // Mostrar o alerta de sucesso
                    alert(data.message);
                    // Opcionalmente, você pode remover o card ou atualizar a página
                    $('#request-' + requestId).fadeOut();
                } else {
                    alert(data.message); // Exibir a mensagem de erro
                }
            },
            error: function() {
                alert("Ocorreu um erro ao processar a solicitação!");
            }
        });
    });

    $(".reject-btn").click(function() {
        var requestId = $(this).data("id");
        $.ajax({
            url: 'process_premium.php', // Novo arquivo PHP que vai lidar com a rejeição
            type: 'POST',
            data: {
                action: 'reject',
                request_id: requestId
            },
            success: function(response) {
                // Garantir que o retorno seja um JSON
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    // Mostrar o alerta de sucesso
                    alert(data.message);
                    // Opcionalmente, você pode remover o card ou atualizar a página
                    $('#request-' + requestId).fadeOut();
                } else {
                    alert(data.message); // Exibir a mensagem de erro
                }
            },
            error: function() {
                alert("Ocorreu um erro ao processar a solicitação!");
            }
        });
    });

    </script>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
