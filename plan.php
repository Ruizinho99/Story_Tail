<?php

include_once("user_logged_in.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=lock" />
    <link rel="stylesheet" href="Styles/style.css">
    <link rel="stylesheet" href="Styles/headers.css">
    <title> Plan - Storytails</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="Styles/plan.css">
</head>
<body>
<?php 
    include_once 'header_choose.php'
    ?>

    <!-- Navigation Tabs -->
    <div class="container mt-3">
        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item">
                <a class="nav-link " href="edit_profile.php">Edit Profile</a>
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
                <a class="nav-link active" href="plan.php">Plan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="help.php">Help</a>
            </li>
        </ul>
    </div>
    <div class="container py-5">
        <h1 class="text-center font-weight-bold mb-4">Plan & Pricing</h1>
        
        <div class="row justify-content-center">
            <!-- Tabela de Planos -->
            <div class="col-md-8">
                <table class="table table-bordered text-center pricing-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Free</th>
                            <th>Premium</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Free</td>
                            <td class="bg-light"><span class="text-danger">&#10008;</span></td>
                            <td><span class="text-success">&#10004;</span></td>
                        </tr>
                        <tr>
                            <td>Read Premium Books</td>
                            <td class="bg-light"><span class="text-danger">&#10008;</span></td>
                            <td><span class="text-success">&#10004;</span></td>
                        </tr>
                        <tr>
                            <td>Able to request a Book upload</td>
                            <td class="bg-light"><span class="text-danger">&#10008;</span></td>
                            <td><span class="text-success">&#10004;</span></td>
                        </tr>
                        <tr>
                            <td>Able to request a Book upload</td>
                            <td class="bg-light"><span class="text-danger">&#10008;</span></td>
                            <td><span class="text-success">&#10004;</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        <!-- Seleção de Planos -->
    <div class="col-md-4">
     <div class="plan-container card p-4">
        <h5 class="text-center mb-4 font-weight-bold">Select Your Plan</h5>
        <!-- Linha para o plano Anual -->
        <div class="plan-option mb-2">
            <div class="plan-info">
                <span class="plan-title">Annual</span>
                <small class="text-success">Save 50%</small>
            </div>
            <span class="plan-price">3€ / Month</span>
            <button class="btn btn-success btn-sm">Buy Now</button>
        </div>
        <!-- Linha para o plano Mensal -->
        <div class="plan-option mb-2">
            <div class="plan-info">
                <span class="plan-title">Monthly</span>
            </div>
            <span class="plan-price">6€ / Month</span>
            <button class="btn btn-success btn-sm">Buy Now</button>
        </div>
        <!-- Linha para o plano Grátis -->
        <div class="plan-option mb-2">
            <div class="plan-info">
                <span class="plan-title">Free</span>
            </div>
            <span class="plan-price">0€ / Month</span>
            <button class="btn btn-warning btn-sm">Current</button>
        </div>
    </div>
</div>
</div>
</div>
<!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<div>
    <?php include 'footer.html'; ?> 
</div>
</body>
</html>