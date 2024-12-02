<?php
// Incluir o arquivo de conexão
include('db_connection.php');

// Nome da base de dados
$dbname = 'LP';

// 1. Criar a base de dados 'LP' caso ela não exista
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";

// Verifica se a criação da base de dados foi bem-sucedida
if ($conn->query($sql_create_db) === TRUE) {
    echo "Base de dados '$dbname' criada ou já existe.<br>";
} else {
    die("Erro ao criar a base de dados: " . $conn->error);
}

// 2. Selecionar a base de dados para que as tabelas sejam criadas nela
$conn->select_db($dbname);

// 3. Ler o conteúdo do arquivo SQL que contém as instruções para as tabelas
$sql = file_get_contents('01_database.sql');

// 4. Executar as consultas do arquivo SQL
if ($conn->multi_query($sql)) {
    echo "Tabelas criadas com sucesso!";
} else {
    echo "Erro ao criar tabelas: " . $conn->error;
}

// Fechar a conexão
$conn->close();
?>
