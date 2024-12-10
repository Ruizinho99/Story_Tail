<?php
// Inclui a conexão com o banco de dados
include_once 'db_connection.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera os dados do formulário
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $publicationYear = $_POST['publication_year'] ?? '';
    $ageGroup = $_POST['age_group'] ?? '';
    $accessLevel = $_POST['access_level'] ?? '0';
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    $description = $_POST['description'] ?? '';
    $coverUrl = null;

    try {
        // Faz o upload da imagem, se houver
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            // Verifica se a extensão da imagem é permitida
            if (in_array($imageExtension, $allowedExtensions)) {
                $uploadDir = 'uploads/book_covers/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // Cria o diretório se não existir
                }
                $newImageName = uniqid('book_', true) . '.' . $imageExtension;
                $coverUrl = $uploadDir . $newImageName;

                if (!move_uploaded_file($imageTmpPath, $coverUrl)) {
                    throw new Exception("Erro ao fazer o upload da imagem.");
                }
            } else {
                throw new Exception("Formato de imagem não permitido. Use JPG, PNG ou GIF.");
            }
        }

        // Insere os dados no banco de dados
        $stmt = $conn->prepare("INSERT INTO books (title, author, publication_year, age_group, access_level, is_active, description, cover_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssississ', $title, $author, $publicationYear, $ageGroup, $accessLevel, $isActive, $description, $coverUrl);

        if ($stmt->execute()) {
            echo "<script>alert('Livro adicionado com sucesso!'); window.location.href='add_books.php';</script>";
            exit;
        } else {
            throw new Exception("Erro ao salvar o livro: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "<script>alert('Erro: " . htmlspecialchars($e->getMessage()) . "'); window.history.back();</script>";
        exit;
    }
}
?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/add_books.css">
</head>

<body>
<?php include_once 'admin_dashboard.php'; ?>

    
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h3>Add New Book</h3>
                </div>
                <div class="card-body">
                <form method="POST" action="add_books.php" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" id="title" name="title" class="form-control" placeholder="Enter title" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="author" class="form-label">Author</label>
                                    <input type="text" id="author" name="author" class="form-control" placeholder="Enter author" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="publication_year" class="form-label">Publication Year</label>
                                    <input type="number" id="publication_year" name="publication_year" class="form-control" placeholder="Enter year" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="age_group" class="form-label">Age Group</label>
                                    <input type="text" id="age_group" name="age_group" class="form-control" placeholder="Enter age group">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="access_level" class="form-label">Access Level</label>
                                    <select id="access_level" name="access_level" class="form-control">
                                        <option value="0">Free</option>
                                        <option value="1">Premium</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="is_active" class="form-label">Is Active</label>
                                    <div class="form-check">
                                        <input type="checkbox" id="is_active" name="is_active" class="form-check-input">
                                        <label for="is_active" class="form-check-label">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <label for="image" class="form-label">Book Cover</label>
                                <div class="image-placeholder border rounded mx-auto">
                                    <img id="book-cover-preview" src="https://via.placeholder.com/200x300.png?text=Book+Cover" alt="Book Cover Preview">
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="file" id="image" name="image" class="form-control" onchange="previewImage(event)">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="5" placeholder="Enter book description"></textarea>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn" style="background-color:#E1700F; color:white"><b>Save</b></button>
                        <button type="reset" class="btn btn-secondary"><b>Cancel</b></button>
                    </div>
                </form>


                </div>
            </div>
        </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById("book-cover-preview");

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
