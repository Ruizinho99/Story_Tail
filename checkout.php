<?php
// Incluindo a conexão com o banco de dados
include('db_connection.php');
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirecionar para login se não estiver logado
    exit;
}

// Recuperar os dados do usuário logado
$user_id = $_SESSION['user_id'];
$sql = "SELECT first_name, last_name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user_data = $result->fetch_assoc();

// Definir plano e preço com base na URL
$plan = isset($_GET['plan']) ? $_GET['plan'] : 'monthly'; // Plano padrão é mensal
$price = 0;

if ($plan == 'annual') {
    $price = 3; // Preço anual: 3€/mês
    $priceText = 'Plano Anual: 3€ / Mês';
} else {
    $price = 6; // Preço mensal: 6€/mês
    $priceText = 'Plano Mensal: 6€ / Mês';
}

// Processamento do formulário ao clicar no botão "Finalizar Solicitação"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar os dados do formulário
    $subject = "Plano Premium"; // Assunto fixo para solicitação de mudança de plano
    $message = "O usuário está solicitando a alteração para o plano premium."; // Mensagem padrão

    // Inserir a solicitação na tabela 'request'
    $sql = "INSERT INTO request (user_id, subject, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $subject, $message);
    
    if ($stmt->execute()) {
        // Se a solicitação for inserida com sucesso, mostrar a mensagem de revisão
        $success_message = "Sua solicitação está sendo analisada.";
    } else {
        // Se ocorrer algum erro, mostrar a mensagem de erro
        $error_message = "Ocorreu um erro com a sua solicitação. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="Styles/headers.css">
    <link rel="stylesheet" href="Styles/style.css">
    <title>Solicitação - Alteração de Plano</title>

    <style>
        body {
            background: linear-gradient(135deg, #f3f4f6, #e2e8f0);
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .alert-info {
            background-color: #f1f8fe;
            color: #1e3a8a;
            border-left: 4px solid #2563eb;
        }
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .price-display {
            font-size: 2rem;
            color: #E1700F;
        }
        .btn-primary, .btn-secondary {
            font-size: 16px;
            padding: 12px 30px;
            border-radius: 25px;
            transition: background-color 0.3s ease;
        }
        .btn-primary {
            background-color: #E1700F;
            color: white;
        }
        .btn-primary:hover {
            background-color: #c85d00;
        }
        .btn-secondary {
            background-color: #6b7280;
        }
        .btn-secondary:hover {
            background-color: #4b5563;
        }
    </style>
</head>
<body>
    <?php include_once 'header_choose.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Verificar Dados</h2>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success text-center">
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger text-center">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>
        
        <div class="alert alert-info text-center">
            <strong>Plano Selecionado:</strong> Premium
        </div>
        <form method="POST" action="checkout.php" class="mt-4" id="checkout-form">
            <div class="mb-3 row">
                <div class="col-md-6">
                    <label for="first_name" class="form-label">Primeiro Nome</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($user_data['first_name']) ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label for="last_name" class="form-label">Último Nome</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($user_data['last_name']) ?>" readonly>
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user_data['email']) ?>" readonly>
            </div>

            <!-- Campos do cartão em uma linha -->
            <div class="mb-3 row">
                <div class="col-md-4">
                    <label for="card_number" class="form-label">Número do Cartão</label>
                    <input type="text" class="form-control" id="card_number" name="card_number" pattern="\d{16}" required placeholder="Número do cartão (16 dígitos)">
                </div>
                <div class="col-md-4">
                    <label for="expiration_date" class="form-label">Data de Expiração</label>
                    <input type="month" class="form-control" id="expiration_date" name="expiration_date" required>
                </div>
                <div class="col-md-4">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" class="form-control" id="cvv" name="cvv" pattern="\d{3}" required placeholder="Código de segurança (3 dígitos)">
                </div>
            </div>

            <!-- Preço Final destacado -->
            <div class="mt-4 mb-4">
                <h4 class="text-center">Preço Final</h4>
                <p class="text-center"><strong>Plano:</strong> <?php echo ($plan == 'annual' ? 'Plano Anual' : 'Plano Mensal'); ?></p>
                <p class="text-center"><strong>Preço:</strong> €<?php echo number_format($price, 2); ?></p>
                <p class="text-center"><strong>Frequência:</strong> <?php echo $priceText; ?></p>
            </div>

            <!-- Botão para finalizar a solicitação -->
            <button type="submit" class="btn btn-primary w-100">Finalizar Solicitação</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
