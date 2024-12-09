<?php
include_once("user_logged_in.php"); // Certifica-se de que o usuário está logado
include_once("db_connection.php"); // Conexão com a base de dados

// Obter o ID do usuário logado da sessão
$userId = $_SESSION['user_id'];

// Recuperar o email do usuário logado
$query = "SELECT email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userEmail = $user['email'];
} else {
    $userEmail = ""; // Caso algo dê errado, deixe vazio
}

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados enviados pelo formulário
    $currentPassword = $_POST['password'];
    $newPassword = $_POST['new_password'];

    // Recuperar informações do usuário logado
    $query = "SELECT senha FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: change_password.php?status=error&message=Usuário não encontrado.");
        exit();
    }

    $user = $result->fetch_assoc();
    $hashedPassword = $user['senha'];

    // Verificar se a senha atual está correta
    if (!password_verify($currentPassword, $hashedPassword)) {
        header("Location: change_password.php?status=error&message=Senha atual incorreta.");
        exit();
    }

    // Atualizar a senha do usuário
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $updateQuery = "UPDATE users SET senha = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $hashedNewPassword, $userId);

    if ($updateStmt->execute()) {
        header("Location: change_password.php?status=success&message=Senha alterada com sucesso.");
    } else {
        header("Location: change_password.php?status=error&message=Erro ao alterar a senha. Por favor, tente novamente.");
    }

    $stmt->close();
    $updateStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Change Password - Storytails</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="Styles/headers.css">
    <link rel="stylesheet" href="Styles/change_password.css">
</head>
<body>
<?php include_once 'header_choose.php'; ?>

<!-- Alertas de Mensagem -->
<?php
if (isset($_GET['status']) && isset($_GET['message'])) {
    $statusClass = ($_GET['status'] === 'success') ? 'alert-success' : 'alert-danger';
    echo '<div class="alert ' . $statusClass . ' alert-dismissible fade show mt-4" role="alert">
            ' . htmlspecialchars($_GET['message']) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}
?>

<!-- Navigation Tabs -->
<div class="container mt-3">
    <ul class="nav nav-tabs justify-content-center">
        <li class="nav-item">
            <a class="nav-link" href="edit_profile.php">Edit Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="my_books.php">My Books</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="favorite_books.php">Favorite Books</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="#">Change Password</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="plan.php">Plan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="help.php">Help</a>
        </li>
    </ul>
</div>

<div class="container mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 profile-sidebar text-center">
            <img src="images/storytail-logo-02.png" alt="User Image" class="img-fluid">
        </div>

        <!-- Separator -->
        <div class="col-md-1 d-flex justify-content-center align-items-center">
            <div style="border-left: 2px solid #ddd; height: 100%;"></div>
        </div>

        <!-- Profile Edit Form -->
        <div class="col-md-8">
            <form action="change_password.php" method="POST">
                <div class="form-group mb-4">
                    <label for="Email">Email:</label>
                    <input type="text" class="form-control" name="email" id="Email" value="<?php echo htmlspecialchars($userEmail); ?>" readonly>
                </div>
                <div class="form-group mb-4">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                </div>
                <div class="form-group mb-4">
                    <label for="repeat">New Password:</label>
                    <input type="password" class="form-control" name="new_password" id="new" placeholder="New Password" required>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                    <button type="submit" class="btn btn-warning">Change</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.html'; ?>

<script>
    // Fechar automaticamente o alerta após 5 segundos
    setTimeout(() => {
        const alertBox = document.querySelector('.alert');
        if (alertBox) {
            alertBox.classList.remove('show'); // Fecha visualmente o alerta
            alertBox.classList.add('fade'); // Aplica efeito de transição
        }
    }, 5000); // 5000 ms = 5 segundos
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
