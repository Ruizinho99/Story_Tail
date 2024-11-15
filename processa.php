<?php
// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os valores do formulário
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Verifica se os campos não estão vazios
    if (!empty($subject) && !empty($message)) {
        // Aqui você pode processar os dados, como salvar em um banco de dados ou enviar por email.
        
        // Exemplo de mensagem de confirmação
        echo "<div style='padding: 20px; max-width: 600px; margin: 20px auto; font-family: Arial, sans-serif; border: 1px solid #28a745; border-radius: 5px;'>";
        echo "<h2 style='color: #28a745;'>Mensagem Enviada com Sucesso!</h2>";
        echo "<p><strong>Assunto:</strong> " . htmlspecialchars($subject) . "</p>";
        echo "<p><strong>Mensagem:</strong> " . nl2br(htmlspecialchars($message)) . "</p>";
        echo "<a href='index.html' style='color: #007bff;'>Voltar</a>";
        echo "</div>";
    } else {
        echo "<h2>Por favor, preencha todos os campos.</h2>";
    }
} else {
    echo "<h2>Formulário não enviado corretamente.</h2>";
}
?>
