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

// Processar o formulário de registo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['senha']);

    // Verificar se o nome de utilizador ou o email já existem
    $checkUserQuery = "SELECT * FROM users WHERE user_name = '$username' OR email = '$email'";
    $result = $conn->query($checkUserQuery);

    if ($result->num_rows > 0) {
        // O nome de utilizador ou o email já existem
        $errorMessage = "O nome de utilizador ou o email já existem. Tente novamente.";
    } else {
        // Criar a senha com hash
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Inserir o novo utilizador
        $userTypeId = 2;  // Todos os registos são do tipo "Free" (user_type 2)
        $insertQuery = "INSERT INTO users (user_type_id, first_name, last_name, user_name, email, senha)
                        VALUES ('$userTypeId', '$first_name', '$last_name', '$username', '$email', '$hashedPassword')";

        if ($conn->query($insertQuery) === TRUE) {
            $successMessage = "Conta criada com sucesso. Redirecionando para o login...";
            header("refresh:2;url=login.php"); // Redireciona para a página de login após 2 segundos
            exit();
        } else {
            $errorMessage = "Erro ao criar a conta: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/register.css"> <!-- Usando o mesmo CSS da página de registo -->
    <link rel="stylesheet" href="Styles/headers.css">
    <link rel="stylesheet" href="Styles/style.css">
</head>
<body>
<?php include_once 'header_choose.php'?>
<div class="container-fluid centered-container">
    <section class="centered-section">
        <div class="row w-100 mb-4">
            <div class="col-6"></div>
            <div class="col-2 title-container">
                <h2 class="me-3" style="color: #E1700f;">Register</h2>
                <img src="images/register.png" alt="Register Icon" style="max-width: 145px; height: auto;">
            </div>
        </div>

        <div class="row w-100">
            <div class="col-12 col-md-4 p-0 img-container">
                <img src="images/storytail-logo-02.png" style="max-width: 100%;" class="img-fluid mx-auto d-block" alt="Logo">
            </div>

            <div class="col-md-1 column-spacing"></div>

            <div class="col-12 col-md-7">
                <form method="POST" action="register.php">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="fw-bold">First Name:</label>
                            <input type="text" id="first_name" name="first_name" class="form-control rounded" placeholder="Enter your first name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="fw-bold">Last Name:</label>
                            <input type="text" id="last_name" name="last_name" class="form-control rounded" placeholder="Enter your last name" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="username" class="fw-bold">Username:</label>
                        <input type="text" id="username" name="username" class="form-control rounded" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="fw-bold">Email:</label>
                        <input type="email" id="email" name="email" class="form-control rounded" placeholder="Enter your Email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password" class="fw-bold">Password:</label>
                        <input type="password" id="senha" name="senha" class="form-control rounded" placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn-custom w-100 py-2 rounded">Register</button>

                    <?php if($errorMessage): ?>
                        <div class="alert alert-danger mt-3" role="alert">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($successMessage): ?>
                        <div class="alert alert-success mt-3" role="alert">
                            <?php echo $successMessage; ?>
                        </div>
                    <?php endif; ?>

                    <p class="mt-3 text-center">Already Have an account? <a href="login.php" class="register-link">Login</a>.</p>
                </form>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.html'?>
</body>
</html>
