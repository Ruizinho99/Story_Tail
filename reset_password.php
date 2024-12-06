<?php
// reset_password.php
include_once 'db_connection.php';

// Inicializa a variável $resetMessage para evitar warnings
$resetMessage = "";

// Verifica se o token está presente
if (isset($_GET['token']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_GET['token'];
    $newPassword = $_POST['new_password'];

    // Verifica se o token é válido e ainda não expirou
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($userId);

    if ($stmt->fetch()) {
        $stmt->free_result(); // Libera resultados pendentes

        // Atualiza a senha do usuário
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // Criptografa a nova senha
        $updateStmt = $conn->prepare("UPDATE users SET senha = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $userId);
        $updateStmt->execute();

        if ($updateStmt->affected_rows > 0) {
            // Redireciona para a página de login em caso de sucesso
            $updateStmt->close();
            $stmt->close();
            $conn->close();
            header("Location: login.php?reset=success");
            exit(); // Termina o script após o redirecionamento
        } else {
            $resetMessage = "Erro ao redefinir a senha. Tente novamente.";
        }

        $updateStmt->close();
    } else {
        $resetMessage = "Token inválido ou expirado.";
    }

    $stmt->close();
    $conn->close();
} else {
    $resetMessage = "";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Redefinir Senha</h1>

    <?php if (!empty($resetMessage)): ?>
        <div class="alert alert-info">
            <?php echo $resetMessage; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group mb-3">
            <label for="new_password">Nova Senha:</label>
            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Digite sua nova senha" required>
        </div>
        <button type="submit" class="btn btn-primary">Redefinir Senha</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
