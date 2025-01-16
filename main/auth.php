<?php
session_start(); // Inicia a sessão

// Verifica se o usuário está autenticado
if (!isset($_SESSION['id'])) {
    // Redireciona para a página de login se não estiver autenticado
    header("Location: login3.php");
    exit;
}
