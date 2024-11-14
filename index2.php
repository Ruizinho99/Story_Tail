<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
</head>

<body>
    <main>
        <section class="register-section">
            <div class="form-container">
                <!-- Adiciona a imagem do logotipo -->
                <div class="form-logo">
                    <img src="images/storytail-logo-02.png" alt="Storytail Logo">
                </div>

                <!-- Formulário de registro -->
                <div class="form-content">
                    <div class="register-header">
                        <h1>Register</h1>
                        <img src="images/register.png" alt="Register Icon" class="register-icon">
                    </div>
                    <form>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" placeholder="Enter your email or username">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" placeholder="Enter your Email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" placeholder="Enter your password">
                        </div>
                        <button type="submit" class="login-button">Login</button>
                        <p>Don't have an account? <a href="#">Register</a>.</p>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>

</html>
