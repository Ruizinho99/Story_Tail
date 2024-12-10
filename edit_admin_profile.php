<?php
// Inclui a conexão com o banco de dados
include_once 'db_connection.php';

// Inclui o arquivo para verificar se o usuário está logado
include_once("user_logged_in.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para editar o perfil.");
}

$user_id = $_SESSION['user_id'];

// Recupera os dados do usuário
$sql = "SELECT first_name, last_name, email, user_name, user_photo_url FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

// Verifica se o usuário existe
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $firstName = $user['first_name'];
    $lastName = $user['last_name'];
    $email = $user['email'];
    $userName = $user['user_name'];
    $userPhotoUrl = $user['user_photo_url'];
} else {
    die("Usuário não encontrado.");
}

// Define uma imagem padrão se a URL da foto do usuário for nula
$profileImageUrl = $userPhotoUrl ? 'uploads/' . $userPhotoUrl : 'images/profile.png';

// Exibe a mensagem de sucesso, se existir
if (isset($_SESSION['statusMessage']) && $_SESSION['statusMessage'] !== "") {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
    echo htmlspecialchars($_SESSION['statusMessage']);
    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
    echo "</div>";
    unset($_SESSION['statusMessage']); // Limpa a mensagem após exibir
}

// Processar o envio da imagem, se houver
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
    $upload_dir = 'uploads/';
    $file_name = $_FILES['profileImage']['name'];
    $file_tmp_name = $_FILES['profileImage']['tmp_name'];
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_file_name = uniqid() . '.' . $file_extension;
    $upload_file = $upload_dir . $new_file_name;

    // Verifica se a extensão é permitida
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array(strtolower($file_extension), $allowed_extensions)) {
        if (move_uploaded_file($file_tmp_name, $upload_file)) {
            $file_url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $upload_file;

            // Atualiza a URL da foto no banco de dados
            $sql_update = "UPDATE users SET user_photo_url = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param('si', $file_url, $user_id);
            $stmt->execute();
            $stmt->close();

            // Define uma mensagem de sucesso
            $_SESSION['statusMessage'] = "Foto de perfil atualizada com sucesso!";
            header('Location: edit_admin_profile.php'); // Redireciona para a página de edição de perfil
            exit();
        } else {
            echo "Erro ao mover o arquivo para o diretório.";
        }
    } else {
        echo "Extensão de arquivo não permitida. Apenas JPG, JPEG, PNG, e GIF são permitidos.";
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/add_books.css">
</head>

<?php include_once 'admin_sidebar.php'; ?>

    <!-- Main Content -->
    <section class="container mt-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 profile-sidebar">
                <div class="text-center">
                    <!-- Exibe a foto de perfil do usuário ou imagem padrão -->
                    <img src="<?php echo $userPhotoUrl ? 'uploads/' . basename($userPhotoUrl) : 'images/default-profile.png'; ?>" alt="User Image" id="profileImage" class="rounded-circle" style="width: 150px; height: 150px;">
                    <h5 class="mt-2"><?php echo htmlspecialchars($firstName); ?></h5>

                    <!-- Formulário para fazer upload da imagem -->
                    <form id="profileForm" method="POST" enctype="multipart/form-data">
                        <input type="file" id="fileInput" name="profileImage" accept="image/*" style="display: none;">
                        <div class="form-group">
                            <button type="button" class="btn btn-warning" id="uploadButton">+ New Picture</button>
                        </div>
                        <button type="submit" class="btn btn-warning mt-2">Save Picture</button>
                    </form>
                </div>
            </div>
            <!-- Separator -->
            <div class="col-md-1 d-flex justify-content-center align-items-center">
                <div style="border-left: 2px solid #ddd; height: 100%;"></div>
            </div>
            <!-- Profile Edit Form -->
            <div class="col-md-8">
                <h4>Edit Profile</h4>
                <form method="POST" action="upload_admin_profile.php">

                    <div class="form-row">
                        <div class="row">
                            <!-- Campo First Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstName">First Name:</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" placeholder="Enter first name">
                                </div>
                            </div>
                            <!-- Campo Last Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastName">Last Name:</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" placeholder="Enter last name">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($userName); ?>" placeholder="Enter username">
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                        <button type="submit" class="btn btn-warning">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Trigger the file input when the button is clicked
        document.getElementById('uploadButton').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        // Update the image preview when a new image is selected
        document.getElementById('fileInput').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('profileImage').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
