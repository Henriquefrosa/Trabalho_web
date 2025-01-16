<?php
$servidor = "localhost"; // Servidor MySQL
$usuario = "root";       // Usuário do MySQL
$senha = "";             // Senha do MySQL
$banco = "marketplace"; // Nome do banco de dados

// Criar a conexão
$conexao = new mysqli($servidor, $usuario, $senha, $banco);

// Verificar a conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}


?> 
