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
