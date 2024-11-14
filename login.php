<?php
// Conectar à base de dados
$servername = "localhost";  // Normalmente localhost
$username = "root";         // Username para a base de dados
$password = "";             // Senha da base de dados (caso tenha)
$dbname = "lp";             // Nome da sua base de dados

// Criar a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Variáveis para armazenar mensagens
$successMessage = "";
$errorMessage = "";

// Processar o formulário de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameOrEmail = mysqli_real_escape_string($conn, $_POST['username_or_email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Verificar se o nome de utilizador ou email existe
    $checkUserQuery = "SELECT * FROM users WHERE user_name = '$usernameOrEmail' OR email = '$usernameOrEmail'";
    $result = $conn->query($checkUserQuery);

    if ($result->num_rows > 0) {
        // O utilizador existe, verificar a password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password correta, redirecionar para a página inicial
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['user_name'];
            $_SESSION['user_type_id'] = $user['user_type_id'];
            header("Location: index.php"); // Redireciona para a homepage (alterar o nome do ficheiro conforme necessário)
            exit();
        } else {
            // Password incorreta
            $errorMessage = "Username ou password incorreta.";
        }
    } else {
        // Utilizador não encontrado
        $errorMessage = "Username ou password incorreta.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="register.css"> <!-- Usando o mesmo CSS da página de registo -->
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
                        <input type="password" id="password" name="password" class="form-control rounded" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn-custom w-100 py-2 rounded">Login</button>

                    <?php if($errorMessage): ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>

                    <p class="mt-3 text-center">Don't have an account? <a href="register.php" class="register-link">Register</a>.</p>
                </form>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
