<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grid com Imagens e Formulário</title>
    <!-- Link para o Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilo para garantir que a imagem ocupe toda a altura e largura */
        .img-full {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
        /* Estilo para centralizar a section tanto vertical quanto horizontalmente */
        .centered-container {
            min-height: 100vh; /* Altura mínima de 100% da tela */
            display: flex;
            justify-content: center; /* Centralizar horizontalmente */
            align-items: center; /* Centralizar verticalmente */
        }
        .centered-section {
            max-width: 1000px; /* Largura máxima */
            width: 100%; /* Garantir que ocupe a largura até o máximo de 700px */
            padding: 30px; /* Espaçamento interno */
        }
        .column-spacing {
            padding-left: 15px;
            padding-right: 15px;
        }
        /* Estilo para a imagem ter largura e altura adequadas */
        .img-container {
            max-width: 500px; /* Limitar a largura máxima da imagem */
            height: 100%; /* A altura será proporcional */
        }

        /* Estilo personalizado para o botão */
        .btn-custom {
            background-color: #E1700f;
            color: white;
            border: none;
        }

        .btn-custom:hover {
            background-color: #c85c0a; /* Cor do botão ao passar o mouse */
        }

        /* Estilo para o link de registro */
        .register-link {
            color: #E1700f;
            text-decoration: none; /* Remover sublinhado */
        }

        .register-link:hover {
            color: #c85c0a; /* Cor do link ao passar o mouse */
        }

        /* Estilo para a nova grid e título + imagem */
        .title-container {
            display: flex;
            justify-content: space-between; /* Alinhar título à esquerda e imagem à direita */
            align-items: center;
            margin-bottom: 20px;
        }

        .title-container h2 {
            color: #E1700f; /* Cor do título */
            margin-right: 15px;
            font-size: 2rem; /* Tamanho do título */
        }

        .title-container img {
            max-width: 30px; /* Limitar a largura da imagem */
            height: auto;
        }
    </style>
</head>
<body>

<div class="container-fluid centered-container">
    <section class="centered-section">
        <!-- Nova Grid para o título à esquerda e a imagem à direita -->
        <div class="row w-100 mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <!-- Título à esquerda -->
                <h2 class="me-3" style="color: #E1700f;">Register</h2>
                <!-- Imagem à direita -->
                <img src="images/register.png" alt="Register Icon" style="max-width: 30px; height: auto;">
            </div>
        </div>

        <div class="row w-100">
            <!-- Coluna para a imagem -->
            <div class="col-12 col-md-4 p-0 img-container">
                <img src="images/storytail-logo-02.png" style="max-width: 100%;" class="img-fluid mx-auto d-block" alt="Logo">
            </div>

            <!-- Espaçamento entre a imagem e o formulário -->
            <div class="col-md-1 column-spacing"></div>

            <!-- Coluna para o formulário -->
            <div class="col-12 col-md-7">
                <form>
                    <div class="form-group mb-3">
                        <label for="username" class="fw-bold">Username:</label>
                        <input type="text" id="username" class="form-control" placeholder="Enter your email or username">
                    </div>
                    <div class="form-group mb-3">
                        <label for="email" class="fw-bold">Email:</label>
                        <input type="email" id="email" class="form-control" placeholder="Enter your Email">
                    </div>
                    <div class="form-group mb-3">
                        <label for="password" class="fw-bold">Password:</label>
                        <input type="password" id="password" class="form-control" placeholder="Enter your password">
                    </div>
                    <!-- Alteração do botão -->
                    <button type="submit" class="btn-custom w-100 py-2">Login</button>
                    <p class="mt-3 text-center">Already Have an account? <a href="/login.php" class="register-link">Register</a>.</p>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Link para o JavaScript do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
