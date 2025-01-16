<?php
// Inicia a sessão
session_start();

// Verifique se o ID do produto foi passado pela URL
if (isset($_GET['id_produto'])) {
    // Captura o id_produto da URL e armazena na sessão
    $_SESSION['id_produto'] = $_GET['id_produto'];
    $id_produto = $_GET['id_produto'];
} else {
    echo "ID do produto não encontrado!";
    exit;
}

// Conexão com o banco de dados
include('conexao.php');
require('auth.php');


// Prepara a consulta SQL para pegar as informações do produto
$sql = "SELECT * FROM Produtos WHERE id_produto = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('i', $id_produto);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se o produto foi encontrado
if ($result->num_rows > 0) {
    $produto = $result->fetch_assoc();
} else {
    echo "Produto não encontrado!";
    exit;
}
$sql = "SELECT email FROM Usuario WHERE id_usuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param('i', $produto['id_vendedor']);
$stmt->execute();
$result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        echo "Email não encontrado!";
    }
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
            background-color: #549287;
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

        

        h2 {
            margin-bottom: 10px;
            color: #17224d;
        }
        main {
    padding: 20px;
    background-color: #549287;
    width: 100%;
    height: 85vh; /* Altura da área principal */
    display: flex;
    align-items: center;
    justify-content: center;
}

.container {
    display: flex;
    width: 90%; /* Ajusta a largura */
    max-width: 1200px; /* Limita a largura a 1200px */
    height: 60vh;
    border-radius: 15px;
    overflow: hidden;
    margin: 0 auto; /* Centraliza a área principal */
}


.produto-imagem img {
    width: 100%;
    height: auto; /* Mantém a proporção da imagem */
    max-width: 400px; /* Define um limite máximo de largura */
    object-fit: cover;
    border-radius: 15px;
}


.produto-imagem img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 15px;
}

.produto-info {
    flex: 1; /* Outra metade do espaço */
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    color: #333;
}

.produto-info h1 {
    font-size: 2rem; /* Ajusta o tamanho do título */
}

button {
    padding: 12px 24px; /* Aumenta o tamanho do botão */
    font-size: 1.1rem; /* Ajuste para um tamanho de fonte mais legível */
}

#emailVendedor {
    font-size: 1.1rem; /* Ajusta o tamanho do e-mail exibido */
}


.produto-info p {
    font-size: 1.1rem;
    margin-bottom: 10px;
    line-height: 1.5;
}

.produto-info p strong {
    color: #17224d;
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
        <a href="paginainicial.php" id="meusProdutos">Pagina inicial</a>
            <a href="meusProdutos.php" id="meusProdutos">Meus produtos</a>
            <a href="Pagina de perfil">
                <img id="perfil" src="imagens/logo_de_perfil.png" alt="Logo do perfil">
            </a>
        </div>
    </header>

    <main>
        <div class="container">
        <div class="produto-imagem" style="flex: 1;">
            <img id="imagem" src="<?php echo $produto['foto']; ?>" alt="<?php echo $produto['nome']; ?>">
        </div>
        <div class="produto-info" style="flex: 2;">
            <h1><?php echo $produto['nome']; ?></h1>
            <p><strong>Preco:</strong> R$ <?php echo $produto['preco']; ?></p>
            <p><strong>Categoria:</strong> <?php echo $produto['categoria']; ?></p>
            <p><strong>Estado:</strong> <?php echo $produto['estado']; ?></p>
            <p><strong>Descrição:</strong> <?php echo nl2br($produto['descricao']); ?></p>
        </div>
    </div>
   
    </main> 
    <div style="text-align: center; margin-top: 20px;">
        <button id="btnContato" style="padding: 10px 20px; font-size: 1rem; cursor: pointer;">Contato</button>
        <p id="emailVendedor" style="margin-top: 10px; font-size: 1.1rem; color: #17224d;"></p>
    </div>

    <script>
        // Atribuindo o e-mail do vendedor ao JavaScript
        var emailVendedor = "<?php echo htmlspecialchars($usuario['email']); ?>";
        
        // Função para exibir o e-mail quando o botão for clicado
        document.getElementById('btnContato').addEventListener('click', function() {
            // Mostra o e-mail no elemento com id 'emailVendedor'
            document.getElementById('emailVendedor').textContent = emailVendedor;
        });
    </script>
</body>
</html>