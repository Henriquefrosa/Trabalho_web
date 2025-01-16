<?php 
include 'conexao.php';
?>

<?php

// Verifique se a requisição foi feita via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pegue o corpo da requisição
    $data = json_decode(file_get_contents('php://input'), true);

    // Valide se o e-mail foi enviado
    if (isset($data['email'])) {
        $email = $data['email'];

        // Verifique se o e-mail existe no banco de dados
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // O e-mail existe no banco de dados
            echo json_encode(['success' => true, 'message' => 'E-mail encontrado.']);
        } else {
            // O e-mail não existe
            echo json_encode(['success' => false, 'message' => 'E-mail não encontrado']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'E-mail não fornecido']);
    }
    exit(); // Encerra o script após retornar a resposta
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Senha</title>
    <style>
        /* O estilo continua o mesmo */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #2c6b3f;
        }
        .recovery-container {
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
        .back-to-login {
            margin-top: 10px;
            font-size: 14px;
        }
        .back-to-login a {
            color: #90EE90;
            text-decoration: none;
        }
        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="recovery-container">
        <h1>Recuperação de Senha</h1>
        <form id="recoveryForm">
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Enviar Link de Recuperação</button>
        </form>
        <div class="back-to-login">
            <a href="login2.html">Voltar ao Login</a>
        </div>
    </div>

    <script>
        document.getElementById('recoveryForm').addEventListener('submit', async function (event) {
            event.preventDefault(); // Impede o envio do formulário

            const email = document.getElementById('email').value;

            // Validação simples de email
            const emailRegex = /^[a-zA-Z0-9._%+-]+@edu\.udesc\.br$/;
            if (!emailRegex.test(email)) {
                alert('O e-mail deve ser do domínio @edu.udesc.com');
                return;
            }
        
            try {
                // Envia a requisição de validação de e-mail para o servidor
                const response = await fetch('recuperarSenha.php', {
                    method: 'POST',  // Garantindo que o método seja POST
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email: email })
                });

                if (!response.ok) {
                    // Se o servidor retornar um erro (não 2xx)
                    const errorData = await response.json();
                    alert(errorData.message || 'Erro ao validar o e-mail!');
                    return;
                }

                const result = await response.json();

                if (result.success) {
                    // E-mail válido, continua com o envio do link de recuperação
                    alert('Link de recuperação enviado para o seu e-mail!');
                    window.location.href = 'login2.html'; // Redireciona para o login
                } else {
                    // E-mail não encontrado ou outro erro do servidor
                    alert(result.message || 'Erro ao validar o e-mail!');
                }
            } catch (error) {
                console.error('Erro na requisição:', error);
                alert('Erro ao tentar enviar o e-mail de recuperação. Tente novamente mais tarde.');
            }
        });
    </script>

</body>
</html>
