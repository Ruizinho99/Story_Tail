<?php
// Conexão com a base de dados
include 'db_connection.php'; // arquivo com a conexão à base de dados

// Definir o ID do usuário para teste
$user_id = 1; // Altere para o ID do usuário desejado

// Preparar a chamada ao procedimento SugerirLivros
$sql_sugerir_livros = "CALL SugerirLivros(?)";
$stmt_sugerir_livros = $conn->prepare($sql_sugerir_livros);

// Verificar se a preparação foi bem-sucedida
if ($stmt_sugerir_livros) {
    // Vincular o parâmetro
    $stmt_sugerir_livros->bind_param("i", $user_id);

    // Executar o procedimento
    $stmt_sugerir_livros->execute();

    // Obter o resultado
    $result_sugerir_livros = $stmt_sugerir_livros->get_result();

    // Verificar se há resultados
    if ($result_sugerir_livros->num_rows > 0) {
        echo "<h2>Livros Sugeridos:</h2>";
        // Exibir os resultados
        while ($row = $result_sugerir_livros->fetch_assoc()) {
            echo "ID do Livro: " . $row['book_id'] . "<br>";
            echo "Título: " . $row['book_title'] . "<br>";
            echo "Descrição: " . $row['book_description'] . "<br>";
            echo "<img src='" . $row['book_cover'] . "' alt='Capa do Livro' style='width:100px;'><br>";
            echo "Categoria: " . $row['category_name'] . "<br>";
            echo "Número de Favoritos: " . $row['favorite_count'] . "<br><br>";
        }
    } else {
        echo "Nenhum livro sugerido para o usuário com ID $user_id.";
    }

    // Fechar a declaração
    $stmt_sugerir_livros->close();
} else {
    echo "Erro na preparação da declaração: " . $conn->error;
}

// Fechar a conexão
$conn->close();
?>

