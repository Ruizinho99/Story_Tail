<?php

include_once("user_logged_in.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=lock" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="headers.css">
    <title>Edit Profile - Storytails</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="edit_profile.css">
</head>
<body>
    <div>
        <?php include 'index_header.html'; ?> 
    </div>
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
    <div class="container mt-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 profile-sidebar">
                <div class="text-center">
                    <img src="images/vski.jpeg" alt="User Image">
                    <h5 class="mt-2">Tiago VSKI</h5>
                    <div class="form-group">
                        <button type="button" class="btn btn-warning">+ New Picture</button>
                    </div>
                
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
                    <button type="button" class="btn btn-outline-secondary btn-cancel">Cancel</button>
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
    <div>
    <?php include 'footer.html'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
