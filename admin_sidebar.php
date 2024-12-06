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
    .sidebar a:hover {
      background-color: #007bff;
    }
    .sidebar .nav-item.active .nav-link {
      background-color: #0069d9;
    }
    .dropdown-menu-dark {
      background-color: #343a40;
    }
    .dropdown-item:hover {
      background-color: #007bff;
    }
    .main-content {
      margin-left: 280px;
      padding: 20px;
    }
    li {
      padding-block: 5px;
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
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"/></svg>
          Dashboard
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"/></svg>
          Orders
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"/></svg>
          Products
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#people-circle"/></svg>
          Customers
        </a>
      </li>
    </ul>
    <hr>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong>mdo</strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
        <li><a class="dropdown-item" href="#">New project...</a></li>
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Sign out</a></li>
      </ul>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h1>Welcome to the Admin Dashboard</h1>
    <p>Manage your orders, products, and customers from here.</p>
  </div>

  <!-- Bootstrap JS and custom script to handle active links -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Adiciona a classe 'active' ao link clicado e remove dos outros
    const links = document.querySelectorAll('.nav-link');
    links.forEach(link => {
      link.addEventListener('click', function() {
        links.forEach(item => item.classList.remove('active')); // Remove a classe active de todos
        this.classList.add('active'); // Adiciona a classe active ao item clicado
      });
    });
  </script>
</body>
</html>
