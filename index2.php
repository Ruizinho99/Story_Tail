<?php
// Informações de conexão com o banco de dados
$host = 'localhost'; // ou o IP do servidor MySQL
$username = 'root'; // Nome de usuário do MySQL
$password = ''; // Senha do MySQL
$dbname = 'LP'; // Nome da base de dados

// Criar conexão com o banco de dados
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Consulta para buscar livros
$sql = "SELECT id, title, cover_url, added_at FROM books WHERE is_active = 1 ORDER BY added_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livros - Cards Simples</title>
</head>
<body>
    <h1>Lista de Livros</h1>

    <?php
    // Verificar se há livros no resultado da consulta
    if ($result->num_rows > 0) {
        // Exibir cada livro como um card simples
        while ($row = $result->fetch_assoc()) {
            echo '<div style="border: 1px solid #ccc; padding: 10px; margin: 10px;">';
            echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
            if (!empty($row['cover_url'])) {
                echo '<img src="' . htmlspecialchars($row['cover_url']) . '" alt="' . htmlspecialchars($row['title']) . '" style="max-width: 200px; max-height: 200px;"><br>';
            } else {
                echo '<p>Imagem não disponível</p>';
            }
            echo '<p>Adicionado em: ' . htmlspecialchars($row['added_at']) . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>Nenhum livro encontrado.</p>';
    }

    // Fechar conexão com o banco de dados
    $conn->close();
    ?>

</body>
</html>
