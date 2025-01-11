<?php
// Incluindo a conexão com o banco de dados
include('db_connection.php');
session_start();

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar os dados do formulário
    $user_id = $_SESSION['user_id']; // ID do usuário logado
    $subject = "Plano Premium"; // Assunto fixo para solicitação de mudança de plano
    $message = "O usuário está solicitando a alteração para o plano premium."; // Mensagem padrão

    // Inserir a solicitação na tabela 'request'
    $sql = "INSERT INTO request (user_id, subject, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $subject, $message);
    
    if ($stmt->execute()) {
        // Se a solicitação for inserida com sucesso, mostrar a mensagem de revisão
        $success_message = "Your request is being reviewed.";
    } else {
        // Se ocorrer algum erro, mostrar a mensagem de erro
        $error_message = "There was an error with your request. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=lock" />
    <link rel="stylesheet" href="Styles/headers.css">
    <link rel="stylesheet" href="Styles/style.css">
    <title>Checkout - Plan Request</title>
</head>
<body>
    <?php 
        include_once 'header_choose.php';
    ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6"> <!-- Limitar a largura do form para 6 colunas -->
                <h2 class="text-center">Verificar Dados</h2>
                <div class="alert alert-info text-center">
                    <strong>Plano Selecionado:</strong> <?= htmlspecialchars($plan) ?>
                </div>
                <form method="POST" action="checkout.php" class="mt-4">
                    <!-- Campo oculto para manter o plano selecionado -->
                    <input type="hidden" name="plan" value="<?= htmlspecialchars($plan) ?>">

                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">Primeiro Nome</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Último Nome</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>

                    <!-- Botões alinhados à direita -->
                    <div class="d-flex justify-content-end">
                        <a href="plan.php" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
