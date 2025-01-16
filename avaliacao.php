<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login3.php");
    exit();
}

require_once 'conexao.php'; // Inclui o arquivo de conexão

// Obtém o ID do usuário logado
$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Avaliação de um usuário
    if (isset($_POST['avaliar'])) {
        $id_avaliado = $_POST['id_avaliado'];
        $nota = $_POST['nota'];
        $avaliacao = trim($_POST['mensagem']);

        // Verificar se está tentando se avaliar
        if ($id_avaliado == $user_id) {
            echo "<script>alert('Você não pode avaliar a si mesmo!');</script>";
        } else {
            $stmt = $conexao->prepare(
                "INSERT INTO Avaliacao (nota, avaliacao, id_avaliador, id_avaliado)
                VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("isii", $nota, $avaliacao, $user_id, $id_avaliado);

            if ($stmt->execute()) {
                echo "<script>alert('Avaliação realizada com sucesso!');</script>";
            } else {
                echo "<script>alert('Erro ao realizar avaliação.');</script>";
            }
            $stmt->close();
        }
    }

    // Gerar ranking
    if (isset($_POST['gerar_ranking'])) {
        $order = $_POST['ordenar_por'] === 'nome' ? 'u.nome ASC' : 'media_nota DESC';
        $ranking_sql = "
            SELECT u.nome,
                   AVG(a.nota) AS media_nota,
                   COUNT(a.nota) AS total_avaliacoes
            FROM Usuario u
            LEFT JOIN Avaliacao a ON u.id_usuario = a.id_avaliado
            GROUP BY u.id_usuario
            ORDER BY $order
        ";
        $ranking_result = $conexao->query($ranking_sql);
    }
}

// Obter todos os usuários para a avaliação
$usuarios_result = $conexao->query("SELECT id_usuario, nome FROM Usuario");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliar Usuário</title>
    <style>
        /* Estilos CSS aqui */
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

        h1, h2 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #8B0000;
            font-weight: bold;
        }

        input, select, textarea {
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Avaliar Usuário</h1>
        <form method="POST">
            <label for="id_avaliado">Escolha o Usuário:</label>
            <select name="id_avaliado" id="id_avaliado" required>
                <?php while ($usuario = $usuarios_result->fetch_assoc()): ?>
                    <option value="<?= $usuario['id_usuario'] ?>">
                        <?= htmlspecialchars($usuario['nome']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="nota">Nota (1 a 5):</label>
            <input type="number" name="nota" id="nota" min="1" max="5" required>

            <label for="mensagem">Mensagem (Opcional):</label>
            <textarea name="mensagem" id="mensagem"></textarea>

            <button type="submit" name="avaliar">Avaliar</button>
        </form>

        <h2>Gerar Ranking</h2>
        <form method="POST">
            <label for="ordenar_por">Ordenar por:</label>
            <select name="ordenar_por" id="ordenar_por" required>
                <option value="nome">Nome</option>
                <option value="nota">Nota</option>
            </select>
            <button type="submit" name="gerar_ranking">Gerar Ranking</button>
        </form>

        <?php if (isset($ranking_result) && $ranking_result->num_rows > 0): ?>
            <h3>Ranking de Usuários</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Média da Nota</th>
                        <th>Total de Avaliações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($usuario = $ranking_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td><?= number_format($usuario['media_nota'], 2) ?></td>
                            <td><?= $usuario['total_avaliacoes'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
