<?php require 'auth.php';
    include 'conexao.php';
    
    // Inicia a sessão
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        header {
            width: 100%;
            height: 15vh; /* 15% da altura da tela */
            display: flex;
            justify-content: space-between; /* Separar os elementos nos extremos */
            align-items: center;
            padding: 0 2%;
            box-sizing: border-box;
            background-color: #549287;
            border-bottom: 1px solid black;
        }

        .navegation {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: 40%;
            height: 100%;
            margin-left: auto;
        }

        .navegation a {
            text-decoration: none;
            color: #333;
            margin-right: 5vw;
            font-size: 1rem;
            font-weight: bold;
        }

        #perfil {
            width: 40px;
            height: 40px;
            margin-right: 3vw;
        }

        .pesquisa {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 40%;
            height: 50%;
            border-radius: 20px;
            background-color: #adad97;
        }

        #pesquisa {
            width: 30px;
            height: 30px;
            margin: 0 10px;
        }

        #texto {
            border: 0;
            width: 100%;
            height: 70%;
            font-size: 1.1rem;
            background-color: transparent;
            color: #333;
        }

        #texto::placeholder {
            color: #666;
        }

        main {
    padding: 20px;
    background-color: #549287;
    width: 100%;
    height: 85vh; /* Altura fixa para main */
    overflow: hidden; /* Evitar overflow em main */
    display: flex;
    flex-direction: column; /* Garante que o conteúdo seja organizado verticalmente */
    box-sizing: border-box;
}
        h2 {
            margin-bottom: 10px;
            color: #17224d;
        }

        .selecionados {
    width: 100%;
    height: 60vh; /* Altura para manter os produtos destacados */
    background-color: #247179;
    border-radius: 20px;
    margin: auto 5px;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    overflow-x: auto; /* Permite rolagem horizontal */
    position: relative;
    align-items: center;
    padding: 10px; /* Espaçamento interno para não grudar nos limites */
    box-sizing: border-box; /* Considera o padding na largura total */
}
        .produto-container {
            display: flex;
            width: max-content; /* Garante que os itens se ajustem ao tamanho total */
            box-sizing: border-box;
        }

        .produto {
            flex: 0 0 auto; /* Evita que os itens encolham */
            width: 300px; /* Largura máxima para cada produto */
            margin: 10px;
            text-align: center;
            background-color: #f8f8f8;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            cursor: pointer;
            box-sizing: border-box; /* Garante que o padding não afete a largura */
        }

        .produto img {
            width: 100%;  /* A imagem ocupará 100% da largura do container */
            height: 200px; /* Define a altura fixa para a imagem */
            object-fit: cover; /* As imagens serão cortadas para cobrir toda a área */
        }

        .produto .info {
            padding: 10px;
            background-color: #fff;
        }

    </style>
</head>
<body>
    <header>
        <div class="pesquisa">
            <img id="pesquisa" src="imagens/magnifying-glass-search-free-png.webp" alt="Lupa para ilustrar ferramenta de pesquisa">
            <form action="tela_de_busca.php" method="POST">
                <input id="texto" name="texto_pesquisa" type="text" placeholder="Digite aqui o que você procura">
            </form>
        </div>

        <div class="navegation">
            <a href="avaliacao.php" id="meusProdutos">Avaliar vendedores</a>
            <a href="meusProdutos.php" id="meusProdutos">Meus produtos</a>
            <a href="edicaoCadastro1.php">
                <img id="perfil" src="imagens/logo_de_perfil.png" alt="Logo do perfil">
            </a>
            <a href="logout.php">
                <img id="perfil" src="imagens/logout_icon_151219.webp" alt="Logo do perfil">
            </a>
        </div>
    </header>
    <main>
        <h2>Produtos destaques</h2>
        <div class="selecionados">
            <div class="produto-container" id="produto-container">
                <?php
                // Consulta SQL para pegar os produtos
                $sql = "SELECT id_produto, nome, foto, preco FROM Produtos LIMIT 10";
                $result = $conexao->query($sql);
                if (!$result) {
                    die("Erro na consulta: " . $conexao->error);
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Armazena o ID do produto na sessão ao clicar
                        echo '<a href="paginaproduto.php?id_produto=' . $row['id_produto'] . '">';
                        echo '<div class="produto">';
                        echo '<img src="' . htmlspecialchars($row['foto']) . '" alt="' . htmlspecialchars($row['nome']) . '">';
                        echo '<div class="info">';
                        echo '<h3>' . htmlspecialchars($row['nome']) . '</h3>';
                        echo '<p>R$ ' . htmlspecialchars($row['preco']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                    }
                } else {
                    echo '<p>Nenhum produto disponível.</p>';
                }
                ?>
            </div>
        </div>
    </main>
</body>
</html>
<?php $conexao->close(); ?>
