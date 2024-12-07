<?php
// Inclui a conexão com o banco de dados
include_once 'db_connection.php';

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

// Exibe a mensagem de sucesso, se existir
if (isset($_SESSION['statusMessage']) && $_SESSION['statusMessage'] !== "") {
    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
    echo htmlspecialchars($_SESSION['statusMessage']);
    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
    echo "</div>";
    unset($_SESSION['statusMessage']); // Limpa a mensagem após exibir
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Styles/style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/headers.css">
    <link rel="stylesheet" href="Styles/edit_profile.css">
    <title>Edit Profile - Storytails</title>
</head>
<body>
<?php include_once 'header_choose.php'; ?>

<!-- Navigation Tabs -->
<div class="container mt-3">
    <ul class="nav nav-tabs justify-content-center">
        <li class="nav-item">
            <a class="nav-link active" href="#">Edit Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="my_books.php">My Books</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="favorite_books.php">Favorite Books</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="change_password.php">Change Password</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="plan.php">Plan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="help.php">Help</a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<section class="container mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 profile-sidebar">
            <div class="text-center">
                <!-- Exibe a foto de perfil do usuário ou imagem padrão -->
                <img src="<?php echo $userPhotoUrl ? 'uploads/' . $userPhotoUrl : 'images/default-profile.png'; ?>" alt="User Image" id="profileImage" class="rounded-circle" style="width: 150px; height: 150px;">
                <h5 class="mt-2"><?php echo htmlspecialchars($firstName); ?></h5>
                
                <!-- Form for uploading the image and updating profile details -->
                <form id="profileForm" method="POST" enctype="multipart/form-data" action="upload_profile.php">
                    <!-- Hidden File Input -->
                    <input type="file" id="fileInput" name="profileImage" accept="image/*" style="display: none;">
                    <div class="form-group">
                        <!-- Botão que dispara o input de arquivo -->
                        <button type="button" class="btn btn-warning" id="uploadButton">+ New Picture</button>
                    </div>
                </form>
            </div>
            <hr>
        </div>

        <!-- Profile Edit Form -->
        <div class="col-md-9">
            <h4>Edit Profile</h4>
            <form method="POST" action="upload_profile.php">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="firstName">First Name:</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" placeholder="Enter first name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lastName">Last Name:</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" placeholder="Enter last name">
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
    document.getElementById('uploadButton').addEventListener('click', function () {
        document.getElementById('fileInput').click();
    });

    // Update the image preview when a new image is selected
    document.getElementById('fileInput').addEventListener('change', function (event) {
        const reader = new FileReader();
        reader.onload = function () {
            document.getElementById('profileImage').src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    });
</script>

<!-- Optional JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'footer.html'; ?>
</body>
</html>
