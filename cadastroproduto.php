<?php
require 'auth.php';
include 'conexao.php';

// Inicia a sessão
session_start();

$id_vendedor = $_SESSION['id']; // ID do vendedor a partir da sessão

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $conexao->real_escape_string($_POST['nome']);
    $descricao = $conexao->real_escape_string($_POST['descricao']);
    $categoria = $conexao->real_escape_string($_POST['categoria']);
    $estado = $conexao->real_escape_string($_POST['estado']);
    $preco = $conexao->real_escape_string($_POST['preco']); // Obtém o preço
    
    // Gerencia o upload da foto
    $foto = $_FILES['foto'];
    $caminhoFoto = 'fotos_produtos/' . basename($foto['name']);
    echo $caminhoFoto;

    if ($foto['error'] === UPLOAD_ERR_OK) {
        $nomeUnico = uniqid('produto_', true) . '.' . pathinfo($foto['name'], PATHINFO_EXTENSION);
        $caminhoFoto = 'fotos_produtos/' . $nomeUnico;
    
        if (move_uploaded_file($foto['tmp_name'], $caminhoFoto)) {
            // Insere os dados no banco de dados
            $sql = "INSERT INTO Produtos (nome, descricao, categoria, estado, foto, preco, id_vendedor)
                    VALUES ('$nome', '$descricao', '$categoria', '$estado', '$caminhoFoto', '$preco', '$id_vendedor')";
    
            if ($conexao->query($sql)) {
                echo '<script>alert("Produto cadastrado com sucesso!"); window.location.href="meusProdutos.php";</script>';
            } else {
                echo '<script>alert("Erro ao cadastrar produto: ' . $conexao->error . '");</script>';
            }
        } else {
            echo '<script>alert("Erro ao mover o arquivo para o diretório de destino.");</script>';
        }
    } else {
        echo '<script>alert("Erro no upload da foto: ' . $foto['error'] . '");</script>';
    }
    
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        main {
            padding: 20px;
            background-color: #549287;
            min-height: 85vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        form input, form textarea, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            background-color: #549287;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #417a6a;
        }
    </style>
</head>
<body>
    <header>
        <h1 style="text-align: center; background-color: #549287; padding: 15px; color: #fff;">
            Cadastro de Produto
        </h1>
    </header>
    <main>
        <form action="cadastroproduto.php" method="POST" enctype="multipart/form-data">
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="preco">Preço:</label>
            <input type="number" id="preco" name="preco" step="0.01" min="0" required>


            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="4" required></textarea>

            <label for="categoria">Categoria:</label>
            <input type="text" id="categoria" name="categoria" required>

            <label for="estado">Estado do Produto:</label>
            <select id="estado" name="estado" required>
                <option value="Novo">Novo</option>
                <option value="Usado">Usado</option>
            </select>

            <label for="foto">Foto do Produto:</label>
            <input type="file" id="foto" name="foto" accept="image/*" required>

            <button type="submit">Cadastrar Produto</button>
        </form>
    </main>
</body>
</html>
