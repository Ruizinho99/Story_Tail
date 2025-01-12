<?php
// Conectar ao banco de dados
include_once('db_connection.php');

// Verificar se o usuário é admin (user_type_id = 1)
session_start();
if ($_SESSION['user_type_id'] != 1) {
    header("Location: index.php"); // Redireciona caso não seja admin
    exit;
}

// Consulta SQL para obter informações dos usuários
$sql = "
    SELECT 
        id, 
        user_photo_url, 
        user_name, 
        user_type_id 
    FROM users 
    ORDER BY id ASC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/manage.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container py-5">
        <h2 class="text-center">Manage Users</h2>
        <table class="table table-hover table-dark mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Username</th>
                    <th>User Type</th>
                    <th>Manage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Verificar a existência da foto do usuário
                        $photoUrl = !empty($row['user_photo_url']) && file_exists('uploads/' . basename($row['user_photo_url'])) 
                            ? 'uploads/' . basename($row['user_photo_url']) 
                            : 'uploads/default-profile.png'; // Foto padrão

                        echo "
                        <tr>
                            <td>" . $row['id'] . "</td>
                            <td><img src='" . $photoUrl . "' alt='User Photo' class='img-fluid' style='width: 50px; height: 50px; border-radius: 50%;'></td>
                            <td>" . $row['user_name'] . "</td>
                            <td>" . $row['user_type_id'] . "</td>
                            <td>
                                <div class='dropdown'>
                                    <button class='btn btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton" . $row['id'] . "' data-bs-toggle='dropdown' aria-expanded='false'>
                                        <i class='fas fa-ellipsis-v'></i>
                                    </button>
                                    <ul class='dropdown-menu' aria-labelledby='dropdownMenuButton" . $row['id'] . "'>
                                        <li><a class='dropdown-item update-user-type' href='#' data-id='" . $row['id'] . "' data-type='1'>Admin</a></li>
                                        <li><a class='dropdown-item update-user-type' href='#' data-id='" . $row['id'] . "' data-type='2'>Free User</a></li>
                                        <li><a class='dropdown-item update-user-type' href='#' data-id='" . $row['id'] . "' data-type='3'>Premium User</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        ";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Nenhum usuário encontrado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Função para atualizar o tipo de usuário
        $(document).on('click', '.update-user-type', function(e) {
            e.preventDefault();
            var userId = $(this).data('id');
            var userType = $(this).data('type');

            $.ajax({
                url: 'update_user_type.php', // Endpoint para atualizar o tipo de usuário
                type: 'POST',
                data: { id: userId, type: userType },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        alert(data.message);
                        location.reload(); // Atualiza a página
                    } else {
                        alert(data.message);
                    }
                },
                error: function() {
                    alert('Erro ao atualizar o tipo de usuário.');
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
