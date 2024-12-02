<?php
// verificar_login.php

session_start(); // Iniciar a sessão para verificar se o usuário está logado

// Verificar se a variável de sessão está definida (indicando que o usuário está logado)
if (!isset($_SESSION['user_id'])) {
    // Usuário não está logado, redirecionar para a página de login
    header("Location: login.php");
    exit();
}
?>