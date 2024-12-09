<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Sidebar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .sidebar {
      width: 280px;
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      background-color: #343a40;
      padding-top: 20px;
      box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar a {
      color: white;
      font-size: 18px;
      padding: 10px 15px;
      text-decoration: none;
    }

    /* Cor de fundo ativa (ativo) */
    .sidebar .nav-item.active .nav-link {
      background-color: #E1700F;
    }

    /* Cor de hover (transparente) */
    .sidebar a:hover {
      background-color: rgba(225, 112, 15, 0.75); /* #E1700F com 25% de transparência */
    }

    .sidebar .nav-item.active .nav-link {
      background-color: #E1700F;
    }

    .dropdown-menu-dark {
      background-color: #343a40;
    }

    /* Cor de hover laranja nos itens do dropdown */
    .dropdown-item:hover {
      background-color: rgba(225, 112, 15, 0.75);
    }

    .main-content {
      margin-left: 280px;
      padding: 20px;
    }

    li {
      padding-block: 5px;
    }

    .profile-section {
      margin-top: 20px;
      color: white;
      padding-left: 10px;
    }

    .profile-options a {
      font-size: 16px;
      display: block;
      padding: 5px 10px;
      color: white;
      text-decoration: none;
    }

    /* Cor de hover laranja nas opções do perfil */
    .profile-options a:hover {
      background-color: rgba(225, 112, 15, 0.75);
      border-radius: 4px;
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-bg-dark">
    <span class="fs-4">Admin Dashboard</span>

    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item">
        <a href="#" class="nav-link active" aria-current="page">
          <svg class="bi pe-none me-2" width="16" height="16">
            <use xlink:href="#home" />
          </svg>
          Home
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          <svg class="bi pe-none me-2" width="16" height="16">
            <use xlink:href="#speedometer2" />
          </svg>
          Edit books
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          <svg class="bi pe-none me-2" width="16" height="16">
            <use xlink:href="#table" />
          </svg>
          Add Books
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          <svg class="bi pe-none me-2" width="16" height="16">
            <use xlink:href="#people-circle" />
          </svg>
          Help Request
        </a>
  </li>
    </ul>

    <!-- User Profile Section -->
    <hr>
    <div class="profile-section">
      <div class="d-flex align-items-center">
        <img src="https://github.com/mdo.png" alt="User Avatar" width="32" height="32" class="rounded-circle me-2">
        <strong>mdo</strong>
      </div>
      <div class="profile-options mt-2">
        <a href="#">New project...</a>
        <a href="#">Settings</a>
        <a href="#">Profile</a>
        <hr class="dropdown-divider">
        <a href="#">Sign out</a>
      </div>
    </div>
  </div>



  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
