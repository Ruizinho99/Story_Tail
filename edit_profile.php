
<?php

include_once("user_logged_in.php");
// Script atualizaçao de perfil 

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
<?php 
    include_once 'header_choose.php'
    ?>
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
                <img src="images/vski.jpeg" alt="User Image" id="profileImage" class="rounded-circle" style="width: 150px; height: 150px;">
                <h5 class="mt-2">Tiago VSKI</h5>
                
                <!-- Form for uploading the image and updating profile details -->
                <form id="profileForm" method="POST" enctype="multipart/form-data" action="upload_profile.php">
                    <!-- Hidden File Input -->
                    <input type="file" id="fileInput" name="profileImage" accept="image/*" style="display: none;">

                    <div class="form-group">
                        <!-- Button triggers the file input -->
                        <button type="button" class="btn btn-warning" id="uploadButton">+ New Picture</button>
                    </div>
            </div>
            <hr>
        </div>

        <!-- Profile Edit Form -->
        <div class="col-md-9">
            <h4>Edit Profile</h4>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="firstName">First Name:</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lastName">Last Name:</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <div>
    <?php include 'footer.html'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // JavaScript to trigger file input click on button click
        document.getElementById('uploadButton').addEventListener('click', function () {
            document.getElementById('fileInput').click();
        });

        // Preview the uploaded image
        document.getElementById('fileInput').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profileImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
