<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Storytails</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" >
    <style>
        /* Custom CSS para ajustar o estilo */
        .navbar {
            background-color: #f27a1a;
        }
        .navbar-brand {
            color: #fff !important;
            font-weight: bold;
        }
        .nav-link {
            color: #000000 !important;
        }
        .search-bar {
            max-width: 300px;
        }
        .nav-tabs {
            border-bottom: 2px solid #000000;
        }
        .nav-tabs .nav-item {
            margin-right: 20px;
        }
        .nav-tabs .nav-link {
            color: black;
            font-weight: bold;
        }
        .nav-tabs .nav-link.active {
            border-bottom: 3px solid #000000;
        }
        .profile-sidebar {
            text-align: center;
            border-right: 1px solid #e0e0e0;
            padding: 20px;
        }
        .profile-sidebar img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }
        .btn-warning {
            background-color: #f27a1a;
            border-color: #f27a1a;
        }
    </style>
</head>
<body>
    
<?php include 'sl_header.html'; ?> 
    



<!-- Navigation Tabs -->
<div class="container mt-3">
    <ul class="nav nav-tabs justify-content-center">
        <li class="nav-item">
            <a class="nav-link active" href="#">Edit Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">My Books</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Favorite Books</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Change Password</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Plan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Help</a>
        </li>
    </ul>
</div>

<!-- Main Content -->
<div class="container mt-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 profile-sidebar">
            <div class="text-center">
                <img src="https://via.placeholder.com/80" alt="User Image"  >
                <h5 class="mt-2">Cátia Vanessa</h5>
                <a href="#" class="text-success font-weight-bold">Edit Profile</a>
            </div>
            <hr>
            
        </div>

        <!-- Profile Edit Form -->
        <div class="col-md-9">
            <h4>Edit Profile</h4>
            <form>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="firstName">First Name:</label>
                        <input type="text" class="form-control" id="firstName" placeholder="Enter first name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lastName">Last Name:</label>
                        <input type="text" class="form-control" id="lastName" placeholder="Enter last name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label>Profile Picture:</label><br>
                    <button type="button" class="btn btn-warning">+ New Picture</button>
                </div>
                <button type="button" class="btn btn-outline-secondary">Cancel</button>
                <button type="submit" class="btn btn-warning">Apply</button>
            </form>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>