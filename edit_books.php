<?php
// Inclui a conexão com o banco de dados
include_once 'db_connection.php';

// Verifica se o parâmetro book_id foi passado na URL
$bookId = isset($_GET['book_id']) ? $_GET['book_id'] : null;
$bookData = null;

// Se o ID do livro for fornecido, busca os detalhes do livro
if ($bookId) {
    $query = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $bookData = $result->fetch_assoc();
    } else {
        echo "Livro não encontrado!";
        exit;
    }
} else {
    echo "ID do livro não fornecido!";
    exit;
}

// Inicializa as variáveis para mensagens
$successMessage = '';
$errorMessage = '';

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'save') {
        // Atualiza os dados do livro
        $title = $_POST['title'];
        $author = $_POST['author'];
        $publicationYear = $_POST['publication_year'];
        $ageGroup = $_POST['age_group'];
        $accessLevel = $_POST['access_level'];
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $description = $_POST['description'];

        // Upload de imagem, se enviada
        $coverUrl = $bookData['cover_url'];
        if (!empty($_FILES['cover_url']['name'])) {
            $coverDir = "uploads/";
            $coverUrl = $coverDir . basename($_FILES['cover_url']['name']);
            move_uploaded_file($_FILES['cover_url']['tmp_name'], $coverUrl);
        }

        // Atualiza os dados do livro na base de dados
        $updateQuery = "UPDATE books SET title = ?, author = ?, publication_year = ?, age_group = ?, access_level = ?, is_active = ?, description = ?, cover_url = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ssississi", $title, $author, $publicationYear, $ageGroup, $accessLevel, $isActive, $description, $coverUrl, $bookId);

        if ($updateStmt->execute()) {
            $successMessage = "Livro atualizado com sucesso!";
        } else {
            $errorMessage = "Erro ao atualizar o livro. Por favor, tente novamente.";
        }
    }
}

// Código de exclusão do livro (delete logic)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $deleteQuery = "DELETE FROM books WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $bookId);

    if ($deleteStmt->execute()) {
        $successMessage = "Livro excluído com sucesso!";
        // Redireciona para a página de livros após excluir
        header('Location: books.php');
        exit;
    } else {
        $errorMessage = "Erro ao excluir o livro. Tente novamente!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Styles/add_books.css">
</head>
<body>
<?php include_once 'admin_dashboard.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Book</h3>
                </div>
                <div class="card-body">
                    <!-- Exibe mensagens -->
                    <?php if ($successMessage): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
                    <?php endif; ?>
                    <?php if ($errorMessage): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="save">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" id="title" name="title" class="form-control" placeholder="Enter title" value="<?= htmlspecialchars($bookData['title']) ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="author" class="form-label">Author</label>
                                        <input type="text" id="author" name="author" class="form-control" placeholder="Enter author" value="<?= htmlspecialchars($bookData['author']) ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="publication_year" class="form-label">Publication Year</label>
                                        <input type="number" id="publication_year" name="publication_year" class="form-control" placeholder="Enter year" value="<?= htmlspecialchars($bookData['publication_year']) ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="age_group" class="form-label">Age Group</label>
                                        <input type="text" id="age_group" name="age_group" class="form-control" placeholder="Enter age group" value="<?= htmlspecialchars($bookData['age_group']) ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="access_level" class="form-label">Access Level</label>
                                        <select id="access_level" name="access_level" class="form-control">
                                            <option value="0" <?= $bookData['access_level'] == 0 ? 'selected' : '' ?>>Free</option>
                                            <option value="1" <?= $bookData['access_level'] == 1 ? 'selected' : '' ?>>Premium</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="is_active" class="form-label">Is Active</label>
                                        <div class="form-check">
                                            <input type="checkbox" id="is_active" name="is_active" class="form-check-input" <?= $bookData['is_active'] ? 'checked' : '' ?>>
                                            <label for="is_active" class="form-check-label">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 text-center">
                                <div class="mb-3">
                                    <label for="cover_url" class="form-label">Book Cover</label>
                                    <div class="image-placeholder border rounded mx-auto">
                                        <img id="book-cover-preview" src="<?= htmlspecialchars($bookData['cover_url']) ?>" alt="Book Cover Preview" class="img-fluid">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <input type="file" id="cover_url" name="cover_url" class="form-control" onchange="previewImage(event)">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="5" placeholder="Enter book description"><?= htmlspecialchars($bookData['description']) ?></textarea>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn" style="background-color:#E1700F; color:white"><b>Save</b></button>
                            <button type="reset" class="btn btn-secondary"><b>Cancel</b></button>
                            <button type="submit" class="btn btn-danger" name="action" value="delete" onclick="return confirm('Are you sure you want to delete this book?')"><b>Delete</b></button>
                        </div>
                    </form>
                </div>
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
