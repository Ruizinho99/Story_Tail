<?php
// login.php
session_start();

// Incluir o arquivo de validação de login
include_once 'validar_login.php';

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Mensagem para o modal de recuperação de senha
$forgotPasswordMessage = '';

// Processar o formulário de recuperação de senha
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot_password_email'])) {
    include_once 'db_connection.php';

    $email = $_POST['forgot_password_email'];

    // Procurar o email na base de dados
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($userId);

    if ($stmt->fetch()) {
        // Gerar token e tempo de expiração
        $token = bin2hex(random_bytes(16)); // Token aleatório
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expira em 1 hora

        // Atualizar a tabela do usuário com o token
        $stmt->free_result();
        $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE id = ?");
        $updateStmt->bind_param("ssi", $token, $expiry, $userId);
        $updateStmt->execute();

        // Enviar o link de redefinição por e-mail
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '95f867ddc7d6e0'; // Substituir pelo seu Mailtrap Username
            $mail->Password = 'a2cc02c5128675'; // Substituir pelo seu Mailtrap Password
            $mail->Port = 2525;

            $mail->setFrom('no-reply@seusite.com', 'Story Tail');
            $mail->addAddress($email);
            $mail->Subject = 'Redefinição de Senha';
            $mail->Body = "Clique no link para redefinir sua senha: http://localhost/Story_Tail/reset_password.php?token=$token";

            $mail->send();
            $forgotPasswordMessage = "Um link de redefinição de senha foi enviado ao seu e-mail.";
        } catch (Exception $e) {
            $forgotPasswordMessage = "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
        }

        $updateStmt->close();
    } else {
        $forgotPasswordMessage = "E-mail não encontrado na base de dados.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/register.css"> <!-- Usando o mesmo CSS da página de registo -->
</head>
<body>
<div class="container-fluid centered-container">
    <section class="centered-section">
        <div class="row w-100 mb-4">
            <div class="col-12 title-container">
                <h2 class="me-3" style="color: #E1700f;">Login</h2>
                <img src="images/register.png" alt="Login Icon" style="max-width: 30px; height: auto;">
            </div>
        </div>

        <div class="row w-100">
            <div class="col-12 col-md-4 p-0 img-container">
                <img src="images/storytail-logo-02.png" style="max-width: 100%;" class="img-fluid mx-auto d-block" alt="Logo">
            </div>

            <div class="col-md-1 column-spacing"></div>

            <div class="col-12 col-md-7">
                <form method="POST" action="login.php">
                    <div class="form-group mb-3">
                        <label for="username_or_email" class="fw-bold">Username ou Email:</label>
                        <input type="text" id="username_or_email" name="username_or_email" class="form-control rounded" placeholder="Enter your username or email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password" class="fw-bold">Password:</label>
                        <input type="password" id="senha" name="senha" class="form-control rounded" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn-custom w-100 py-2 rounded">Login</button>

                    <?php if($errorMessage): ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>

                    <p class="mt-3 text-center">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a>
                    </p>
                    <p class="mt-3 text-center">Don't have an account? <a href="register.php" class="register-link">Register</a>.</p>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Modal de recuperação de senha -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="login.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Recuperação de Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="forgot_password_email">Digite seu email:</label>
                        <input type="email" id="forgot_password_email" name="forgot_password_email" class="form-control" placeholder="Enter your email" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($forgotPasswordMessage): ?>
    <div class="alert alert-info text-center mt-3">
        <?php echo $forgotPasswordMessage; ?>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
