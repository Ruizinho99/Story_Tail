<?php
// check_header.php

// Iniciar a sessão para verificar o status de login
session_start();

// Verificar se o usuário está logado
if (isset($_SESSION['user_id'])) {
    // Se o usuário estiver logado, incluir o cabeçalho 'header.html'
    include_once 'header.html';
} else {
    // Se o usuário não estiver logado, incluir o cabeçalho 'sl_header.html'
    include_once 'sl_header.html';
}
?>
