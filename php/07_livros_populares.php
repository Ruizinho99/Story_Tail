<?php
// Conexão com a base de dados
include 'db_connection.php';

// SQL para obter os livros mais populares
$sql_popularidade = "SELECT * FROM LivrosMaisPopulares LIMIT 10";
$result_popularidade = $conn->query($sql_popularidade);

// Verificar se há resultados
if ($result_popularidade->num_rows > 0) {
    echo "<h2>Livros Mais Populares:</h2>";
    while ($row = $result_popularidade->fetch_assoc()) {
        echo "ID do Livro: " . $row['book_id'] . "<br>";
        echo "Título: " . $row['book_title'] . "<br>";
        echo "Descrição: " . $row['book_description'] . "<br>";
        echo "<img src='" . $row['book_cover'] . "' alt='Capa do Livro' style='width:100px;'><br>";
        echo "Tempo de Leitura: " . $row['book_read_time'] . " minutos<br>";
        echo "Leituras: " . $row['total_leituras'] . "<br>";
        echo "Favoritos: " . $row['total_favoritos'] . "<br>";
        echo "Popularidade Total: " . $row['total_popularidade'] . "<br><br>";
    }
} else {
    echo "Nenhum livro encontrado.";
}

// Fechar a conexão
$conn->close();
?>
