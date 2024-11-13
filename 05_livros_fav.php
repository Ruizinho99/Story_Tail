<?php
// Conexão com a base de dados
include 'db_connection.php'; // arquivo com a conexão à base de dados

// Definir o ID do usuário para teste
$user_id = 1; // Altere para o ID do usuário desejado

// Preparar a chamada ao procedimento de livros favoritos
$sql_favoritos = "CALL ListarLivrosFavoritos(?)";
$stmt_favoritos = $conn->prepare($sql_favoritos);

// Verificar se a preparação foi bem-sucedida
if ($stmt_favoritos) {
    // Vincular o parâmetro
    $stmt_favoritos->bind_param("i", $user_id);

    // Executar o procedimento
    $stmt_favoritos->execute();

    // Obter o resultado
    $result_favoritos = $stmt_favoritos->get_result();

    // Verificar se há resultados
    if ($result_favoritos->num_rows > 0) {
        echo "<h2>Livros Favoritos:</h2>";
        // Exibir os resultados
        while ($row = $result_favoritos->fetch_assoc()) {
            echo "ID do Livro: " . $row['book_id'] . "<br>";
            echo "Título: " . $row['book_title'] . "<br>";
            echo "Descrição: " . $row['book_description'] . "<br>";
            echo "URL da Capa: " . $row['book_cover'] . "<br>";
            echo "Ano de Publicação: " . $row['book_year'] . "<br>";
            echo "Grupo de Idade: " . $row['book_age_group'] . "<br>";
            echo "Status: " . ($row['book_status'] ? 'Ativo' : 'Inativo') . "<br>";
            echo "Avaliação: " . ($row['book_rating'] ? $row['book_rating'] : 'Não Avaliado') . "<br>";
            echo "ID do Usuário: " . $row['user_id'] . "<br><br>";
        }
    } else {
        echo "Nenhum livro favorito encontrado para o usuário com ID $user_id.";
    }

    // Fechar a declaração
    $stmt_favoritos->close();
} else {
    echo "Erro na preparação da declaração: " . $conn->error;
}

// Fechar a conexão
$conn->close();
?>
