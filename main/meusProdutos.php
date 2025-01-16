<?php
require 'auth.php';
include 'conexao.php';

// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: login3.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Produtos</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #549287;
        }

        header {
            width: 100%;
            height: 15vh;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 0 2%;
            box-sizing: border-box;
            background-color: #549287;
            border-bottom: 1px solid black;
        }

        h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #fff;
            margin: auto;
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


        main {
            padding: 20px;
            background-color: #549287;
            width: 100%;
            height: 85vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .produto {
            width: 90%;
            height: 15%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f8f8;
            border-radius: 10px;
            margin-bottom: 10px;
            padding: 10px;
        }

        .produto img {
            width: 20%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .info {
            flex: 1;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info h3 {
            margin: 0;
            font-size: 1.2rem;
        }

        .info p {
            margin: 0;
            font-size: 1rem;
            color: #333;
        }

        .excluir-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .excluir-btn:hover {
            background-color: #c0392b;
        }
        a{
            margin-left: auto;
        }

    </style>
</head>
<body>
    <header>
        <h1>Meus Produtos</h1>
        <a href="paginainicial.php" >
        <button>Voltar para pagina inicial</button>
</a>
    </header>
    <main>
        <?php
        // Obtém o id_usuario da sessão
        $id_usuario = $_SESSION['id'];

        // Consulta SQL para pegar os produtos do usuário
        $sql = "SELECT id_produto, nome, foto, preco FROM Produtos WHERE id_vendedor = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="produto">';
                echo '<img src="' . htmlspecialchars($row['foto']) . '" alt="' . htmlspecialchars($row['nome']) . '">';
                echo '<div class="info">';
                echo '<h3>' . htmlspecialchars($row['nome']) . '</h3>';
                echo '<p>R$ ' . htmlspecialchars($row['preco']) . '</p>';
                echo '<form action="excluir_produto.php" method="POST" onsubmit="return confirm(\'Você tem certeza que deseja excluir este produto?\');">';
                echo '<input type="hidden" name="id_produto" value="' . $row['id_produto'] . '">';
                echo '<button class="excluir-btn" type="submit">Excluir Pedido</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>Nenhum produto encontrado.</p>';
        }

        $stmt->close();
        ?>
            <div class="add-product-container">
    <button onclick="window.location.href='cadastroproduto.php'">Adicionar Novo Produto</button>
</div>

    </main>
</body>
</html>

<?php $conexao->close(); ?>
