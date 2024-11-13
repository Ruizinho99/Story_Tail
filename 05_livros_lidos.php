<?php
// Conexão com a base de dados
include 'db_connection.php'; // arquivo com a conexão à base de dados

// Definir o ID do usuário para teste
$user_id = 1; // Altere para o ID do usuário desejado

// Preparar a chamada ao procedimento de livros lidos
$sql_lidos = "CALL ListarLivrosLidos(?)";
$stmt_lidos = $conn->prepare($sql_lidos);

// Verificar se a preparação foi bem-sucedida
if ($stmt_lidos) {
    // Vincular o parâmetro
    $stmt_lidos->bind_param("i", $user_id);

    // Executar o procedimento
    $stmt_lidos->execute();

    // Obter o resultado
    $result_lidos = $stmt_lidos->get_result();

    // Verificar se há resultados
    if ($result_lidos->num_rows > 0) {
        echo "<h2>Livros Lidos:</h2>";
        // Exibir os resultados
        while ($row = $result_lidos->fetch_assoc()) {
            echo "ID do Livro: " . $row['book_id'] . "<br>";
            echo "Título: " . $row['book_title'] . "<br>";
            echo "Descrição: " . $row['book_description'] . "<br>";
            echo "URL da Capa: " . $row['book_cover'] . "<br>";
            echo "Ano de Publicação: " . $row['book_year'] . "<br>";
            echo "Grupo de Idade: " . $row['book_age_group'] . "<br>";
            echo "Status: " . ($row['book_status'] ? 'Ativo' : 'Inativo') . "<br>";
            echo "Progresso do Usuário: " . $row['user_progress'] . " páginas<br>";  // Exibe o progresso
            echo "Classificação do Usuário: " . ($row['user_rating'] ? $row['user_rating'] : 'Não Avaliado') . "/5<br>";
            echo "Data de Leitura: " . ($row['user_read_date'] ? $row['user_read_date'] : 'Não Avaliado') . "<br><br>";
        }
    } else {
        echo "Nenhum livro lido encontrado para o usuário com ID $user_id.";
    }

    // Fechar a declaração
    $stmt_lidos->close();
} else {
    echo "Erro na preparação da declaração: " . $conn->error;
}

// Fechar a conexão
$conn->close();
?>


