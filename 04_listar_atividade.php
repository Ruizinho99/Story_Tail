<?php
// Conexão com a base de dados
include 'db_connection.php'; // arquivo com a conexão à base de dados

// Definir o ID do livro para teste
$book_id = 1; // Altere para o ID do livro desejado

// Preparar a chamada ao procedimento de listar atividades do livro
$sql_atividades = "CALL ListarAtividadesLivro(?)";
$stmt_atividades = $conn->prepare($sql_atividades);

// Verificar se a preparação foi bem-sucedida
if ($stmt_atividades) {
    // Vincular o parâmetro
    $stmt_atividades->bind_param("i", $book_id);

    // Executar o procedimento
    $stmt_atividades->execute();

    // Obter o resultado
    $result_atividades = $stmt_atividades->get_result();

    // Verificar se há resultados
    if ($result_atividades->num_rows > 0) {
        echo "<h2>Atividades do Livro:</h2>";
        // Exibir os resultados
        while ($row = $result_atividades->fetch_assoc()) {
            echo "ID da Atividade: " . $row['activity_id'] . "<br>";
            echo "Título: " . $row['activity_title'] . "<br>";
            echo "Descrição: " . $row['activity_description'] . "<br>";
            // Não exibir 'activity_assigned_at' porque foi removido
            echo "ID do Usuário: " . $row['user_id'] . "<br>";
            echo "Progresso do Usuário: " . ($row['user_progress'] !== null ? $row['user_progress'] . "%" : "Não iniciado") . "<br><br>";
        }
    } else {
        echo "Nenhuma atividade encontrada para o livro com ID $book_id.";
    }

    // Fechar a declaração
    $stmt_atividades->close();
} else {
    echo "Erro na preparação da declaração: " . $conn->error;
}

// Fechar a conexão
$conn->close();
?>
