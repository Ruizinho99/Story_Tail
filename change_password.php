<?php

include_once("user_logged_in.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Storytails</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome, Material Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="headers.css">
    <link rel="stylesheet" href="change_password.css">
</head>
<body>
    <?php include 'index_header.html'; ?> 

    <!-- Navigation Tabs -->
    <div class="container mt-3">
        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="edit_profile.php">Edit Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">My Books</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Favorite Books</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#">Change Password</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="plan.php">Plan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="help.php">Help</a>
            </li>
        </ul>
    </div>

    <div class="container mt-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 profile-sidebar text-center">
                <img src="images/storytail-logo-02.png" alt="User Image" class="img-fluid">
            </div>

            <!-- Separator -->
            <div class="col-md-1 d-flex justify-content-center align-items-center">
            <div style="border-left: 2px solid #ddd; height: 100%;"></div>
            </div>

            <!-- Profile Edit Form -->
            <div class="col-md-8">
                <form>
                    <div class="form-group mb-4">
                        <label for="Email">Email:</label>
                        <input type="text" class="form-control" id="Email" placeholder="Enter email">
                    </div>
                    <div class="form-group mb-4">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    <div class="form-group mb-4">
                        <label for="repeat">Repeat Password:</label>
                        <input type="password" class="form-control" id="repeat" placeholder="Repeat Password">
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-outline-secondary me-2">Cancel</button>
                        <button type="submit" class="btn btn-warning">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.html'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>