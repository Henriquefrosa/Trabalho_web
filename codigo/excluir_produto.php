<?php
require 'auth.php';
include 'conexao.php';

// Verifica se o usuário está logado
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login3.php");
    exit();
}

// Verifica se o id_produto foi enviado via POST
if (isset($_POST['id_produto'])) {
    $id_produto = $_POST['id_produto'];

    // Prepara a query para excluir o produto
    $sql = "DELETE FROM Produtos WHERE id_produto = ?";
    $stmt = $conexao->prepare($sql);

    // Vincula os parâmetros para a query (id_produto e id_vendedor)
    $stmt->bind_param("i", $id_produto);

    // Executa a query
    if ($stmt->execute()) {
        // Exibe uma mensagem de sucesso e redireciona
        echo "<script>alert('Produto excluído com sucesso!'); window.location.href='meus_produtos.php';</script>";
    } else {
        // Se ocorrer algum erro, exibe uma mensagem
        echo "<script>alert('Erro ao excluir o produto. Tente novamente.'); window.location.href='meus_produtos.php';</script>";
    }

    $stmt->close();
} else {
    // Se o id_produto não foi enviado, redireciona de volta
    header("Location: meus_produtos.php");
    exit();
}

$conexao->close();
?>
