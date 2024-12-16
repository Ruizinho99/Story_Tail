<?php
// Incluir a conexão com o banco de dados
include('db_connection.php');
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['response'])) {
    // Obter o ID do pedido de ajuda e a resposta
    $request_id = $_POST['request_id'];
    $response = $_POST['response'];

    // Consulta para pegar as informações do pedido
    $sql = "SELECT r.subject, r.message, u.first_name, u.last_name, u.email
            FROM request r
            JOIN users u ON r.user_id = u.id
            WHERE r.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();

    // Verifica se o pedido foi encontrado
    if (!$request) {
        die("Pedido não encontrado.");
    }

    // Enviar o e-mail com a resposta
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';  // Substitua pelo seu servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = '95f867ddc7d6e0';  // Substitua pelo seu Mailtrap Username
        $mail->Password = 'a2cc02c5128675';  // Substitua pelo seu Mailtrap Password
        $mail->Port = 2525;

        $mail->setFrom('no-reply@seusite.com', 'Story Tail');
        $mail->addAddress($request['email']);  // E-mail do usuário
        $mail->Subject = 'Resposta ao Pedido de Ajuda: ' . $request['subject'];
        $mail->Body = "Olá " . $request['first_name'] . ",\n\n";
        $mail->Body .= "Aqui está a sua resposta:\n\n";
        $mail->Body .= $response . "\n\n";
        $mail->Body .= "Obrigado por nos contatar.\n\n";
        $mail->send();

        $responseMessage = "Sua resposta foi enviada para o e-mail do usuário.";
    } catch (Exception $e) {
        $responseMessage = "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
    }
} else {
    $responseMessage = '';
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.90">
    <title>Responder Pedido de Ajuda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 800px;
            margin-top: 50px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
            box-shadow: none;
        }

        .btn-submit {
            background-color: #E1700F;
            border-color: #E1700F;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 8px;
            width: 100%;
        }

        .btn-submit:hover {
            background-color: #D2600C;
            border-color: #D2600C;
        }

        .card {
            background-color: #f8f9fa;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .message {
            max-height: 150px;
            overflow-y: auto;
            white-space: pre-wrap;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
        }

        .message p {
            margin: 0;
        }
    </style>
</head>
<body>
    <?php include 'header_choose.php'?>
    <div class="container">
        <div class="header">
            <h2>Responder ao Pedido de Ajuda</h2>
        </div>

        <!-- Exibir as informações do pedido -->
        <div class="card">
            <div class="card-body">
                <h5><strong>Assunto:</strong> <?php echo $request['subject']; ?></h5>
                <div class="message">
                    <strong>Mensagem do Usuário:</strong>
                    <p><?php echo nl2br($request['message']); ?></p>
                </div>
                <p><strong>Enviado por:</strong> <?php echo $request['first_name'] . ' ' . $request['last_name']; ?> (<?php echo $request['email']; ?>)</p>
            </div>
        </div>

        <form action="send_response.php" method="POST">
            <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">

            <div class="mb-3">
                <label for="response" class="form-label">Sua Resposta</label>
                <textarea id="response" name="response" class="form-control" rows="6" required></textarea>
            </div>

            <button type="submit" class="btn btn-submit" style="color: white">Enviar Resposta</button>

            <?php if($responseMessage): ?>
                <div class="alert alert-info mt-3" role="alert">
                    <?php echo $responseMessage; ?>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <?php include 'footer.html'?>
</body>
</html>
