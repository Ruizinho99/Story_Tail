<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e1e1e;
            color: #f2f2f2;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
        }

        .container {
            margin-top: 30px;
        }

        .card {
            background-color: #282828;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #1d1d1d;
            border-radius: 12px 12px 0 0;
            text-align: center;
            color: #E1700F;
        }

        .form-label {
            color: #E1700F;
        }

        .form-control {
            background-color: #333;
            color: white;
            border: 1px solid #444;
        }

        .form-control:focus {
            border-color: #E1700F;
            box-shadow: 0 0 5px rgba(225, 112, 15, 0.8);
        }

        .btn-primary {
            background-color: #E1700F;
            border-color: #E1700F;
        }

        .btn-primary:hover {
            background-color: rgba(225, 112, 15, 0.8);
            border-color: rgba(225, 112, 15, 0.8);
        }

        /* Contêiner do Placeholder da Imagem */
        .image-placeholder {
            width: 100%; /* Preenche a largura do contêiner */
            max-width: 200px; /* Define o tamanho máximo */
            aspect-ratio: 2 / 3; /* Mantém a proporção 2:3 */
            background-color: #1e1e1e; /* Fundo escuro */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border: 2px solid white; /* Borda branca */
            border-radius: 4px; /* Bordas arredondadas */
        }

        /* Estilo da Imagem */
        .image-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Garante que a imagem preencha o espaço */
            border-radius: 4px; /* Bordas arredondadas */
        }
    </style>
</head>

<body>
    <?php include_once 'admin_sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h3>Add New Book</h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <!-- Labels Section -->
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" id="title" class="form-control" placeholder="Enter title">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="author" class="form-label">Author</label>
                                        <input type="text" id="author" class="form-control" placeholder="Enter author">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="publication_year" class="form-label">Publication Year</label>
                                        <input type="number" id="publication_year" class="form-control" placeholder="Enter year">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="age_group" class="form-label">Age Group</label>
                                        <input type="text" id="age_group" class="form-control" placeholder="Enter age group">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="access_level" class="form-label">Access Level</label>
                                        <select id="access_level" class="form-control">
                                            <option value="0">Free</option>
                                            <option value="1">Premium</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="is_active" class="form-label">Is Active</label>
                                        <div class="form-check">
                                            <input type="checkbox" id="is_active" class="form-check-input">
                                            <label for="is_active" class="form-check-label">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Section -->
                            <div class="col-md-4 text-center">
                                <!-- Label da Imagem -->
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <!-- Placeholder da Imagem -->
                                    <div class="image-placeholder border rounded mx-auto">
                                        <img id="book-cover-preview" src="https://via.placeholder.com/200x300.png?text=Book+Cover" alt="Book Cover Preview">
                                    </div>
                                </div>
                                <!-- Input de Upload de Imagem -->
                                <div class="mb-3">
                                    <input type="file" id="image" class="form-control" onchange="previewImage(event)">
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea id="description" class="form-control" rows="5" placeholder="Enter book description"></textarea>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn " style="background-color:#E1700F; color:white"><b>Save</b></button>
                            <button type="reset" class="btn btn-secondary"><b>Cancel</b></button>
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
