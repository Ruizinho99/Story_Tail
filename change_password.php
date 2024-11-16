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
    <title>Change Password - Storytails</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="change_password.css">
</head>
<body>
    
    <?php include 'index_header.html'; ?> 

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
        <div class="col-md-3 profile-sidebar">
            <div class="text-center">
                <img src="images/storytail-logo-02.png" alt="User Image">
               
            </div>
            <hr>
        </div>

        <!-- Profile Edit Form -->
        <div class="col-md-9">
            <form>
                <div class="form-group">
                    <label for="Email">Email:</label>
                    <input type="text" class="form-control" id="Email" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <label for="repeat">Repeat Password:</label>
                    <input type="password" class="form-control" id="repeat" placeholder="Repeat Password">
                </div>
                <button type="button" class="btn btn-outline-secondary btn-cancel">Cancel</button>
                <button type="submit" class="btn btn-warning">Change</button>
            </form>
        </div>
    </div>
</div>

    <?php include 'footer.html'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
