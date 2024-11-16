<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - Storytails</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="help.css">
    
</head>
<body>
    
    <?php include 'header.html'; ?> 

    <!-- Navigation Tabs -->
    <div class="container mt-3">
        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item">
                <a class="nav-link " href="#">Edit Profile</a>
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
                <a class="nav-link active" href="#">Help</a>
            </li>
        </ul>
    </div>
    <style>
        /* Cor personalizada para o título */
        .custom-title {
            color: #E1700F;
            font-weight: bold;
            text-align: center;
        }

        /* Cor personalizada para o botão */
        .custom-btn {
            background-color: #E1700F !important; /* 'important' para garantir que o estilo seja aplicado */
            border: none;
            color: #fff !important;
            font-weight: bold;
        }

        .custom-btn:hover {
            background-color: #d45f0e !important; /* Um tom mais escuro para o efeito hover */
        }
    </style>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="col-md-6">
            <h2 class="text-center custom-title mb-4">Send Us a Message</h2>
            <form action="processa.php" method="POST" class="border p-4 shadow-sm rounded">
                <div class="form-group">
                    <label for="subject">What's the subject?</label>
                    <select class="form-control" id="subject" name="subject" required>
                        <option value="" disabled selected>What's the subject?</option>
                        <option value="General Inquiry">General Inquiry</option>
                        <option value="Support">Support</option>
                        <option value="Feedback">Feedback</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">What's your message!</label>
                    <textarea class="form-control" id="message" name="message" rows="5" placeholder="What's your message!" required></textarea>
                </div>
                <button type="submit" class="btn custom-btn btn-block">Send</button>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS and dependencies (optional for extra features) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <div>
    <?php include 'footer.html'; ?>
    </div>
</body>
</html>
