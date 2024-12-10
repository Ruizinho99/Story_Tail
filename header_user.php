<?php
// Inclui a conexão com o banco de dados
include_once 'db_connection.php';

// Inclui o arquivo para verificar se o usuário está logado
include_once("user_logged_in.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Você precisa estar logado para acessar esta página.");
}

$user_id = $_SESSION['user_id'];

// Recupera os dados do usuário, incluindo a foto de perfil
$sql = "SELECT first_name, last_name, user_photo_url FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

// Verifica se o usuário existe
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userName = $user['first_name'] . ' ' . $user['last_name'];
    $userPhotoUrl = !empty($user['user_photo_url']) && file_exists('uploads/' . basename($user['user_photo_url'])) 
        ? 'uploads/' . basename($user['user_photo_url']) 
        : 'images/default-profile.png'; // Foto de perfil ou padrão
} else {
    $userName = 'User';
    $userPhotoUrl = 'images/default-profile.png'; // Imagem padrão caso não tenha foto
}
?>

<style>
  .navbar-nav .nav-link {
    color: white !important;
  }

  .navbar-nav .nav-link:hover {
    color: #f8f9fa !important;
  }

  .navbar-nav .nav-item.dropdown .nav-link {
    color: white !important;
  }

  .navbar-nav .nav-item.dropdown .nav-link:hover {
    color: #f8f9fa !important;
  }
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #E1700F;">
  <div class="container-fluid">
    <a class="navbar-brand text-white fs-4" href="index.php">STORYTAILS</a>

    <!-- Botão de toggle para dispositivos pequenos -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu de navegação colapsável -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
       
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo htmlspecialchars($userPhotoUrl); ?>" alt="User Avatar" width="32" height="32" class="rounded-circle me-2">
            <strong><?php echo htmlspecialchars($userName); ?></strong>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
            <li><a class="dropdown-item" href="my_books.php">My Books</a></li>
            <li><a class="dropdown-item" href="favorite_books.php">Favorite Books</a></li>
            <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
            <li><a class="dropdown-item" href="plan.php">Plan</a></li>
            <li><a class="dropdown-item" href="help.php">Help</a></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
