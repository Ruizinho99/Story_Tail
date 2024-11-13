<?php
// Conexão com a base de dados
include 'db_connection.php'; // arquivo com a conexão à base de dados

// SQL para consultar a view 'HorariosMaisFortes'
$sql = "SELECT * FROM HorariosMaisFortes";

// Executar a consulta
$result = $conn->query($sql);

// Verificar se há resultados
if ($result->num_rows > 0) {
    // Exibir os resultados
    while ($row = $result->fetch_assoc()) {
        echo "Hora: " . $row['hora_do_dia'] . "h<br>";
        echo "Total de Leituras: " . $row['total_leituras'] . "<br>";
        echo "Total de Favoritos: " . $row['total_favoritos'] . "<br>";
        echo "Total de Interações: " . $row['total_interacoes'] . "<br><br>";
    }
} else {
    echo "Nenhuma interação registrada nos últimos 3 meses.";
}

// Fechar a conexão
$conn->close();

?>
