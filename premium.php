<?php
// Conectar ao banco de dados
include_once('db_connection.php');

// Verificar se o usuário é admin (user_type_id = 1)
include('header_choose.php');
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

// Função para aceitar a solicitação e atualizar a tabela subscriptions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_request_id'])) {
    $request_id = intval($_POST['accept_request_id']);

    // Busca o pedido pelo ID
    $sql_request = "SELECT user_id FROM request WHERE id = ?";
    $stmt_request = $conn->prepare($sql_request);
    $stmt_request->bind_param("i", $request_id);
    $stmt_request->execute();
    $result_request = $stmt_request->get_result();

    if ($result_request->num_rows > 0) {
        $request = $result_request->fetch_assoc();
        $user_id = $request['user_id'];

        // Adiciona ou atualiza na tabela subscriptions
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime('+1 year'));
        $plan_id = 1; // ID do plano premium

        // Verificar se o usuário já tem uma assinatura
        $sql_check_subscription = "SELECT id FROM subscriptions WHERE user_id = ?";
        $stmt_check = $conn->prepare($sql_check_subscription);
        $stmt_check->bind_param("i", $user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Se já existe uma assinatura, atualizar a assinatura existente
            $sql_update_subscription = "
                UPDATE subscriptions
                SET plan_id = ?, is_active = 1, start_date = ?, end_date = ?
                WHERE user_id = ?
            ";
            $stmt_update = $conn->prepare($sql_update_subscription);
            $stmt_update->bind_param("issi", $plan_id, $start_date, $end_date, $user_id);
            $stmt_update->execute();
        } else {
            // Se não existe uma assinatura, inserir uma nova
            $sql_insert_subscription = "
                INSERT INTO subscriptions (user_id, plan_id, is_active, start_date, end_date)
                VALUES (?, ?, 1, ?, ?)
            ";
            $stmt_insert = $conn->prepare($sql_insert_subscription);
            $stmt_insert->bind_param("iiss", $user_id, $plan_id, $start_date, $end_date);
            $stmt_insert->execute();
        }

        // Remove o pedido da tabela request
        $sql_delete_request = "DELETE FROM request WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete_request);
        $stmt_delete->bind_param("i", $request_id);
        $stmt_delete->execute();

        echo "<script>alert('Plano premium aceito e atualizado com sucesso!');</script>";
        echo "<script>window.location.href = 'premium.php';</script>";
    } else {
        echo "<script>alert('Pedido não encontrado.');</script>";
    }
}
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
                                <form method='POST'>
                                    <button class='btn btn-success' type='submit' name='accept_request_id' value='" . $row['request_id'] . "'>Aceitar</button>
                                    <button class='btn btn-danger' type='submit' name='reject_request_id' value='" . $row['request_id'] . "'>Rejeitar</button>
                                </form>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Fechar a conexão com o banco de dados
$conn->close();
?>
