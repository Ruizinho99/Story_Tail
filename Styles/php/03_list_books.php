<?php
include 'db_connection.php';

// Passo 2: Chamar o procedimento ListarLivros
$p_title = 'Harry Potter'; // O título que você quer buscar
$p_age_group = NULL;       // Grupo de idade, NULL para não filtrar
$p_is_active = TRUE;       // Filtrar apenas os ativos
$p_access_level = NULL;    // Nível de acesso, NULL para não filtrar
$p_added_at_start = '2023-01-01'; // Data de início
$p_added_at_end = '2023-12-31';   // Data de fim

// Preparar a chamada do procedimento
$sql = "CALL ListarLivros(?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sbiiss", $p_title, $p_age_group, $p_is_active, $p_access_level, $p_added_at_start, $p_added_at_end);

// Executar o procedimento
$stmt->execute();

// Passo 3: Obter e exibir os resultados
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // Exibir cada livro encontrado
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . "<br>";
        echo "Título: " . $row["title"] . "<br>";
        echo "Descrição: " . $row["description"] . "<br>";
        echo "URL da Capa: " . $row["cover_url"] . "<br>";
        echo "Grupo de Idade: " . $row["age_group"] . "<br>";
        echo "Ativo: " . ($row["is_active"] ? "Sim" : "Não") . "<br>";
        echo "Nível de Acesso: " . $row["access_level"] . "<br>";
        echo "Adicionado em: " . $row["added_at"] . "<br>";
        echo "Atualizado em: " . $row["updated_at"] . "<br>";
        echo "<hr>"; // Linha horizontal para separar os livros
    }
} else {
    echo "Nenhum livro encontrado.";
}

// Fechar o statement e a conexão
$stmt->close();
$conn->close();
?>
