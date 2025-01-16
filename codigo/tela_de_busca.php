<?php
require 'auth.php';
include 'conexao.php';

// Inicia a sessão
session_start();

// Verifica se foi enviado um termo de pesquisa
$texto_pesquisa = isset($_POST['texto_pesquisa']) ? $_POST['texto_pesquisa'] : '';

// Consulta SQL para pegar os produtos que contêm o texto na descrição (nome ou descrição)
$sql = "SELECT id_produto, nome, foto, preco FROM Produtos WHERE nome LIKE ? OR descricao LIKE ? LIMIT 10";
$stmt = $conexao->prepare($sql);

// Sanitizando o texto de pesquisa com o operador LIKE para pesquisa parcial
$texto_pesquisa_param = "%" . $texto_pesquisa . "%";
$stmt->bind_param("ss", $texto_pesquisa_param, $texto_pesquisa_param);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Pesquisa</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        header {
            width: 100%;
            height: 15vh;
            display: flex;
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

        .produto-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .produto {
            flex: 0 0 22%;
            margin: 15px;
            text-align: center;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .produto:hover {
            transform: translateY(-5px);
        }

        .produto img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .produto .info {
            padding: 15px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #333;
        }

        .produto h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .produto p {
            margin: 5px 0;
            font-size: 1rem;
            color: #549287;
        }

        .produto .preco {
            font-size: 1.2rem;
            font-weight: bold;
            color: #17224d;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .produto {
                flex: 0 0 45%;
            }

            .produto img {
                height: 200px;
            }

            h1 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .produto {
                flex: 0 0 90%;
            }

            .produto img {
                height: 180px;
            }

            h1 {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Resultado da Pesquisa</h1>
    </header>
    <main>
        <div class="produto-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<a href="paginaproduto.php?id_produto=' . $row['id_produto'] . '">';
                    echo '<div class="produto">';
                    echo '<img src="' . htmlspecialchars($row['foto']) . '" alt="' . htmlspecialchars($row['nome']) . '">';
                    echo '<div class="info">';
                    echo '<h3>' . htmlspecialchars($row['nome']) . '</h3>';
                    echo '<p class="preco">R$ ' . htmlspecialchars($row['preco']) . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                }
            } else {
                echo '<p>Nenhum produto encontrado.</p>';
            }
            ?>
        </div>
    </main>
</body>
</html>

<?php $conexao->close(); ?>
