    <?php
    // Defina as informações de conexão
    $host = 'localhost'; // ou o IP do servidor MySQL
    $username = 'root';  // Nome de usuário do MySQL
    $password = '';      // Senha do MySQL
    $dbname = 'LP';      // Nome da base de dados que você quer usar

    // Criar a conexão com o MySQL
    $conn = new mysqli($host, $username, $password, $dbname);

    // Verificar se a conexão foi bem-sucedida
    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }
    ?>
