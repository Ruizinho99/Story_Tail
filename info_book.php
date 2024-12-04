<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Links do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-qyE6jl0iAYAwxXV5IFONC8QLwO1Y+AP6QnpXLtttHZWXGHM0LYaxMiW7wOQe0Wy8" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">



    <style>
        .book-details {
    background-color: #f8f9fa;
    padding: 40px 0;
    text-align: left;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    color: #f57c00;
    margin-bottom: 30px;
}

.book-cover {
    max-width: 100%;
    border-radius: 8px;
}

.book-title {
    font-size: 2rem;
    font-weight: bold;
}

.book-author {
    font-size: 1.2rem;
    color: #666;
}

.author-name {
    color: #f57c00;
}

.ratings {
    margin: 10px 0;
    font-size: 1.2rem;
}

.stars {
    color: #ffc107;
}

.book-meta {
    font-size: 1rem;
    color: #666;
    margin: 10px 0;
}

.meta-item {
    display: inline-block;
    margin-right: 15px;
}

.book-description {
    margin: 20px 0;
    font-size: 1rem;
    line-height: 1.5;
    color: #333;
}

.btn-primary {
    background-color: #f57c00;
    color: #fff;
    padding: 10px 20px;
    text-transform: uppercase;
    text-decoration: none;
    font-weight: bold;
    border-radius: 5px;
}

.btn-primary:hover {
    background-color: #e65100;
    color: #fff;
}
    </style>
    
</head>
<body>

<section class="book-details py-5">
    <div class="container">
        <h1 class="section-title text-center mb-4">ABOUT THE BOOK</h1>
        <div class="row align-items-center">
            <!-- Imagem do livro -->
            <div class="col-md-4 text-center">
                <img src="path/to/charlottes-web.jpg" alt="Charlotte's Web" class="img-fluid book-cover">
            </div>
            <!-- Detalhes do livro -->
            <div class="col-md-8">
                <h2 class="book-title">Charlotte's Web</h2>
                <p class="book-author">From: <span class="author-name fw-bold">Garth Williams</span></p>
                <div class="ratings mb-3">
                    <!-- Estrelas -->
                    <span class="stars text-warning">
                        &#9733;&#9733;&#9733;&#9733;&#9734;
                    </span>
                    <span class="rating-value">4/5</span>
                    <span class="rating-count">(45 ratings)</span>
                </div>
                <!-- Informações adicionais -->
                <div class="book-meta mb-3">
                    <span class="meta-item me-3">
                        <i class="material-icons align-middle">menu_book</i> 6 pages
                    </span>
                    <span class="meta-item">
                        <i class="material-icons align-middle">schedule</i> 25 minutes
                    </span>
                </div>
                <!-- Descrição do livro -->
                <p class="book-description">
                    Charlotte's Web is a children's novel by American author E. B. White and illustrated by
                    Garth Williams; it was published on October 15, 1952, by Harper & Brothers. The novel
                    tells the story of a livestock pig named Wilbur and his friendship with a barn spider
                    named Charlotte. When Wilbur is in danger of being slaughtered by the farmer, Charlotte
                    writes messages praising Wilbur (such as "Some Pig") in her web in order to persuade the
                    farmer to let him live.
                </p>
                <!-- Botão de leitura -->
                <a href="reading.php?book_id=1" class="btn btn-primary">READ NOW</a>
            </div>
        </div>
    </div>
</section>

<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoY3XXBQvgEK3QFvdA9VFFxUjCejp5c5F5czEJuvb7IYDI0" crossorigin="anonymous"></script>
</body>
</html>
