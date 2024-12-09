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
    <title>About - Storytails</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .about-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .about-title {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: left;
        }
        .about-content {
            font-size: 1.2rem;
            line-height: 1.8;
            flex-grow: 1; /* Ensures the content takes up remaining space */
            border-left: 1px solid #e0e0e0;
            padding: 40px;
        }
        .vertical-line {
            border-left: 1px solid black; /* Black line with 1px thickness */
            height: auto; /* Adjusts height to content */
            margin: 0 20px;
        }
    </style>
</head>
<body>
<?php include_once 'header_choose.php'?> 

<div class="container my-5">
    <div class="about-container">
        <div class="about-title">
            About
        </div>
        <div class="vertical-line"></div>
        <div class="about-content">
            <p>StoryTail – English Books for Young Learners is a virtual platform offering a library of children's storybooks in English.</p>
            <ul>
                <li>The goal is to provide children, young people, and their parents the opportunity to access a web platform that immerses them in the English language, enriched by the world of fantasy, through:</li>
                <ul>
                    <li>reading;</li>
                    <li>listening and watching videos; and</li>
                    <li>exploring playful and educational activities tailored to the message of each book.</li>
                </ul>
                <li>The web platform allows each user to:</li>
                <ul>
                    <li>search for books;</li>
                    <li>access the content of each book;</li>
                    <li>explore activities;</li>
                    <li>mark books as favorites/read; and</li>
                    <li>use other features.</li>
                </ul>
                <li>This platform also includes a back-office area for administrative users to manage information.</li>
            </ul>
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
