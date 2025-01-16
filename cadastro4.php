<?php
// Ativar exibição de erros para depuração
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Inclui a conexão com o banco de dados
include('conexao.php');

// Processa a requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $senhaConfirmacao = $_POST['senha_confirmacao'];

    // Validação no servidor
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('E-mail inválido!');</script>";
        return;
    }

    if (!preg_match('/^[a-zA-Z0-9._%+-]+@edu\.udesc\.br$/', $email)) {
        echo "<script>alert('O e-mail deve ser do domínio @edu.udesc.br');</script>";
        return;
    }
    

    // Verifica se o e-mail já está cadastrado no banco de dados
    $stmt = $conexao->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('O e-mail já está cadastrado!');</script>";
        // Redireciona para o mesmo formulário, permitindo nova tentativa
        echo "<script>window.history.back();</script>";
        exit;
    }

    // Verifica se a senha é válida
    if (strlen($senha) < 5) {
        echo "<script>alert('A senha deve ter pelo menos 5 caracteres.');</script>";
        return;
    }

    if ($senha !== $senhaConfirmacao) {
        echo "<script>alert('As senhas não coincidem!');</script>";
        return;
    }

    // Criptografa a senha com password_hash, uma forma mais segura de hash
    $senhaHash = hash('sha256', $senha);

    // Insere os dados no banco de dados
    $sql = "INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    
    if ($stmt === false) {
        echo "<script>alert('Erro na preparação da consulta SQL: " . $conexao->error . "');</script>";
        exit;
    }

    $stmt->bind_param('sss', $nome, $email, $senhaHash);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário cadastrado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar usuário: " . $stmt->error . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #2c6b3f;
            height: 100vh;
        }

        .container {
            background-color: #3e8e41;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #8B0000;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #080808;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .login-redirect {
            margin-top: 25px;
            text-align: center;
            font-size: 14px;
        }

        .login-redirect span {
            color: #000000;
        }

        .login-redirect a {
            color: #90EE90;
            text-decoration: none;
            font-weight: bold;
        }

        .login-redirect a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Cadastre-se</h1>
        <form id="registerForm" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required autocomplete="off">

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required autocomplete="off">

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required autocomplete="off">

            <label for="senha_confirmacao">Confirmar Senha:</label>
            <input type="password" id="senha_confirmacao" name="senha_confirmacao" required autocomplete="off">

            <button type="submit">Criar conta</button>
        </form>
        <p id="message" class="message"></p>
    </div>

    <div class="login-redirect">
        <span>Já tem conta? </span>
        <a href="login3.php">Login</a>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            const nome = document.getElementById('nome').value.trim();
            const email = document.getElementById('email').value.trim();
            const senha = document.getElementById('senha').value;
            const senhaConfirmacao = document.getElementById('senha_confirmacao').value;
            const messageElement = document.getElementById('message');

            const emailRegex = /^[a-zA-Z0-9._%+-]+@edu\.udesc\.br$/;
if (!emailRegex.test(email)) {
    messageElement.textContent = 'O e-mail deve ser do domínio @edu.udesc.br.';
    messageElement.style.color = '#ff6347';
    event.preventDefault();
    return;
}



            if (senha.length < 5) {
                messageElement.textContent = 'A senha deve ter pelo menos 5 caracteres.';
                messageElement.style.color = '#ff6347';
                event.preventDefault();
                return;
            }

            if (senha !== senhaConfirmacao) {
                messageElement.textContent = 'As senhas não coincidem.';
                messageElement.style.color = '#ff6347';
                event.preventDefault();
                return;
            }

            messageElement.textContent = 'Enviando...';
            messageElement.style.color = '#32cd32';
        });
    </script>
</body>

</html>
