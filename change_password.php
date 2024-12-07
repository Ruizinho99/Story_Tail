<?php
include_once("db_connection.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$errorMessage = '';
$successMessage = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

    // Verifica se o usuário está logado
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Verifica se o e-mail corresponde ao do usuário logado
        $stmt = $conn->prepare("SELECT email, senha FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($dbEmail, $dbPassword);
        if ($stmt->fetch()) {
            if ($email !== $dbEmail) {
                $errorMessage = "O e-mail não corresponde ao da conta logada.";
            } elseif (!password_verify($currentPassword, $dbPassword)) {
                $errorMessage = "A senha atual está incorreta.";
            } else {
                // Atualiza a senha
                $stmt->close();
                $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                $updateStmt = $conn->prepare("UPDATE users SET senha = ? WHERE id = ?");
                $updateStmt->bind_param("si", $hashedNewPassword, $userId);
                if ($updateStmt->execute()) {
                    $successMessage = "Senha alterada com sucesso!";
                } else {
                    $errorMessage = "Erro ao atualizar a senha.";
                }
                $updateStmt->close();
            }
        } else {
            $errorMessage = "Erro ao buscar informações do usuário.";
        }
        $stmt->close();
    } else {
        $errorMessage = "Usuário não está logado.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/style.css">
    <title>Change Password - Storytails</title>
</head>
<body>
<?php include_once 'header_choose.php'; ?>

<div class="container mt-5">
    <h2>Change Password</h2>
    <?php if ($errorMessage): ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php elseif ($successMessage): ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <form method="POST" action="change_password.php">
        <div class="form-group mb-3">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group mb-3">
            <label for="current_password">Current Password:</label>
            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current password" required>
        </div>
        <div class="form-group mb-3">
            <label for="new_password">New Password:</label>
            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" required>
        </div>
        <button type="submit" class="btn btn-primary">Change</button>
    </form>
</div>

<?php include 'footer.html'; ?>
</body>
</html>
