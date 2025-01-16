<?php
require 'auth.php';
require 'conexao.php'; // Usando conexao.php para conectar ao banco de dados

if (!isset($_SESSION['id'])) {
    header("Location: login3.php");
    exit();
}
$user_id = $_SESSION['id'];
try {
    // Se o formulário foi enviado para atualizar perfil
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['atualizar'])) {
        $nome = trim($_POST['nome']);
        $senha = $_POST['senha'] ?? null;
        $senhaConfirmacao = $_POST['senha_confirmacao'] ?? null;

        // Validação de dados
        if (!empty($senha) && strlen($senha) < 5) {
            $erro = 'A senha deve ter pelo menos 5 caracteres.';
        } elseif (!empty($senha) && $senha !== $senhaConfirmacao) {
            $erro = 'As senhas não coincidem.';
        } else {
            // Atualização de dados no banco
            if (!empty($senha)) {
                $senhaHash = hash('sha256', $senha);
                $sql = "UPDATE Usuario SET nome = ?, senha = ? WHERE id_usuario = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("ssi", $nome, $senhaHash, $user_id);
            } else {
                $sql = "UPDATE Usuario SET nome = ? WHERE id_usuario = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("si", $nome, $user_id);
            }

            if ($stmt->execute()) {
                $sucesso = "Perfil atualizado com sucesso!";
            } else {
                $erro = "Erro ao atualizar o perfil.";
            }
        }
    }

    // Se o formulário foi enviado para excluir perfil
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
        $conexao->begin_transaction();

        try {
            $sql = "DELETE FROM Produtos WHERE id_vendedor = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $sql = "DELETE FROM Usuario WHERE id_usuario = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $conexao->commit();
            echo "<script>alert('Perfil excluído com sucesso!'); window.location.href='login.php';</script>";
            exit;
        } catch (Exception $e) {
            $conexao->rollback();
            $erro = "Erro ao excluir o perfil: " . $e->getMessage();
        }
    }

    // Busca os dados do usuário logado
    $sql = "SELECT nome, email FROM Usuario WHERE id_usuario = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    if (!$usuario) {
        echo "<script>alert('Usuário não encontrado.'); window.location.href='login.php';</script>";
        exit;
    }
} catch (Exception $e) {
    $erro = "Erro: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c6b3f;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        .container {
            background-color: #3e8e41;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            margin-bottom: 20px;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
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

        .delete-button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            background-color: #f44336;
        }

        .delete-button:hover {
            background-color: #e53935;
        }

        .delete-button-container {
            margin-top: 20px;
        }

        .inicio-redirect {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }

        .inicio-redirect span {
            color: #000000;
        }

        .inicio-redirect a {
            color: #90EE90;
            text-decoration: none;
            font-weight: bold;
        }

        .inicio-redirect a:hover {
            text-decoration: underline;
        }

        .alert {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Editar Perfil</h1>
        <?php if ($erro): ?>
            <div class="alert"><?= $erro ?></div>
        <?php endif; ?>
        <form method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required autocomplete="off">

            <label for="senha">Nova Senha (Deixe em branco se não quiser alterar):</label>
            <input type="password" id="senha" name="senha" autocomplete="off">

            <label for="senha_confirmacao">Confirmar Nova Senha:</label>
            <input type="password" id="senha_confirmacao" name="senha_confirmacao" autocomplete="off">

            <button type="submit" name="atualizar">Salvar alterações</button>
        </form>

        <div class="delete-button-container">
            <form method="POST" onsubmit="return confirm('Você tem certeza de que deseja excluir sua conta? Esta ação é irreversível!');">
                <button type="submit" name="excluir" class="delete-button">Excluir Conta</button>
            </form>
        </div>
    </div>

    <!-- Fora do container, a frase e o link para voltar ao início -->
    <div class="inicio-redirect">
        <span>Voltar ao Início?</span>
        <a href="paginainicial.php">Início</a>
    </div>
</body>

</html>
