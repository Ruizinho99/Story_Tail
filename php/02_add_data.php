<?php
include 'db_connection.php';

// Passo 1: Inserir dados na tabela 'user_types' (Alterado para refletir mais tipos, se necessário)
$sql_user_types = "INSERT INTO user_types (user_type) VALUES 
('Admin'), 
('Leitor')";

if ($conn->query($sql_user_types) === TRUE) {
    echo "2 tipos de usuários inseridos com sucesso!<br>";
} else {
    echo "Erro ao inserir tipos de usuários: " . $conn->error . "<br>";
}

// Passo 2: Inserir dados na tabela 'users' com os IDs de user_type corretos
$sql_users = "INSERT INTO users (user_type_id, first_name, last_name, user_name, email, password) VALUES 
(1, 'João', 'Silva', 'joaosilva', 'admin@admin.com', 'senha123a'), 
(2, 'Maria', 'Souza', 'mariasouza', 'maria@example.com', 'senha123'), 
(2, 'Carlos', 'Pereira', 'carlospereira', 'carlos@example.com', 'senha123')";

if ($conn->query($sql_users) === TRUE) {
    echo "3 usuários inseridos com sucesso!<br>";
} else {
    echo "Erro ao inserir usuários: " . $conn->error . "<br>";
}

// Passo 3: Inserir dados na tabela 'authors'
$sql_authors = "INSERT INTO authors (first_name, last_name, description, author_photo_url, nationality) VALUES 
('Ana', 'Lima', 'Autora de livros infantis.', 'http://example.com/ana.jpg', 'Brasileira'), 
('Pedro', 'Alves', 'Escritor de ficção científica.', 'http://example.com/pedro.jpg', 'Português'), 
('Lucia', 'Fernandes', 'Escritora de Romance.', 'http://example.com/lucia.jpg', 'Espanhola')";

if ($conn->query($sql_authors) === TRUE) {
    echo "3 autores inseridos com sucesso!<br>";
} else {
    echo "Erro ao inserir autores: " . $conn->error . "<br>";
}

// Fechar a conexão
$conn->close();
?>
