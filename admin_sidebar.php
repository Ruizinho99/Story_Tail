<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Sidebar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="Styles/admin.css">
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-bg-dark">
    <span class="fs-4">Admin Dashboard</span>

    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      <li class="nav-item">
        <a href="admin.php" class="nav-link active" aria-current="page">
          <svg class="bi pe-none me-2" width="16" height="16">
            <use xlink:href="#home" />
          </svg>
          Home
        </a>
      </li>
      <li>
        <a href="add_books.php" class="nav-link text-white">
          <svg class="bi pe-none me-2" width="16" height="16">
            <use xlink:href="#speedometer2" />
          </svg>
          Add books
        </a>
      </li>
      <li>
        <a href="books.php" class="nav-link text-white">
          <svg class="bi pe-none me-2" width="16" height="16">
            <use xlink:href="#table" />
          </svg>
          Books
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
        <a href="logout.php">Sign out</a>
      </div>
    </div>
  </div>



  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
