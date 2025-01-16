<?php
// Incluir o arquivo de conexão
require_once 'conexao.php';
session_start();
// Definir variáveis de erro para exibir mensagens
$erroEmail = '';
$erroSenha = '';


// Processa o login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    // Verifica se o e-mail existe no banco de dados
    $sql = "SELECT * FROM usuario WHERE email = ?";
    $stmt = $conexao->prepare($sql);

    if ($stmt) {
        $stmt->bind_param('s', $email); // Substituir o placeholder pelo e-mail
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) {
            $erroEmail = 'E-mail não encontrado. Tente novamente.';
        } else {
            $usuario = $resultado->fetch_assoc();

            // Verifica se a senha está correta
            $senhaHash = hash('sha256', $senha);
            if ($senhaHash !== $usuario['senha']) {
                $erroSenha = 'Senha incorreta. Tente novamente.';
            } else {
                // Inicia a sessão e redireciona para o painel
                
                $_SESSION['id'] = $usuario['id_usuario']; // Ajuste para o nome correto da coluna
                header('Location: paginainicial.php');
                exit();
            }
        }

        $stmt->close();
    } else {
        // Caso a preparação da consulta falhe
        echo "<script>alert('Erro ao preparar a consulta.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #2c6b3f;
        }

        .left-side {
            flex: 1;
            background-color: #3e8e41;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .left-side img {
            max-width: 80%;
            max-height: 80%;
        }

        .right-side {
            flex: 1;
            background-color: #2c6b3f;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background-color: #3e8e41;
            padding: 30px;
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

        .forgot-password {
            margin-top: 10px;
            font-size: 14px;
        }

        .forgot-password a {
            color: #90EE90;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .cad-redirect {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }

        .cad-redirect span {
            color: #000000;
        }

        .cad-redirect a {
            color: #90EE90;
            text-decoration: none;
            font-weight: bold;
        }

        .cad-redirect a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #ff0000;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="left-side">
        <img src="imagens/logo.png" alt="Logo" />
    </div>

    <div class="right-side">
        <div class="login-container">
            <h1>Login</h1>
            <form id="loginForm" method="POST" action="">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>

                <button type="submit">Entrar</button>
            </form>

            <!-- Mensagens de erro -->
            <?php if ($erroEmail): ?>
                <div class="error-message"><?php echo $erroEmail; ?></div>
            <?php endif; ?>
            <?php if ($erroSenha): ?>
                <div class="error-message"><?php echo $erroSenha; ?></div>
            <?php endif; ?>

            <div class="forgot-password">
                <a href="recuperarSenha.php">Esqueceu a senha?</a>
            </div>

            <div class="cad-redirect">
                <span>Não tem conta? </span>
                <a href="cadastro4.php">Cadastre-se</a>
            </div>
        </div>
    </div>
</body>

</html>