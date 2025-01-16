# M@rketUdesc: O Hub Acadêmico para Publicação de Produtos e Serviços com Facilidade

## Sobre o Projeto
O M@rketUdesc é um projeto acadêmico desenvolvido em grupo como parte final da disciplina de Desenvolvimento Web na Universidade do Estado de Santa Catarina (UDESC). Seu principal objetivo é proporcionar aos alunos uma oportunidade prática de aplicar e aprimorar conhecimentos adquiridos sobre ferramentas e linguagens relacionadas ao desenvolvimento web. A plataforma foi idealizada como um ambiente virtual para simular a criação de um espaço onde alunos, professores e funcionários poderiam anunciar e comercializar produtos e serviços de forma prática e direcionada, com foco no aprendizado e na integração das tecnologias estudadas durante o curso.

## Funcionalidades Principais
- Cadastro e login de usuários com validação de e-mail institucional.
- Publicação, busca e exclusão de produtos e serviços.
- Edição de perfil de usuários.
- Avaliação de vendedores e compradores.
- Exclusão de contas de usuário.


## Tecnologias Utilizadas
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Banco de Dados:** MySQL


## Requisitos do Sistema
1. **Requisitos de Conteúdo**:
   - Informações do usuário: Nome, e-mail, departamento, avaliações.
   - Informações dos anúncios: Nome, descrição, preço, imagens.

2. **Requisitos Funcionais**:
   - RF01: Cadastro de usuários.
   - RF02: Login seguro e criptografado.
   - RF03: Edição de perfil.
   - RF04: Publicação de produtos e serviços.
   - RF05: Busca com filtros (categoria, preço, popularidade).
   - RF06: Avaliação de transações.
   - RF07: Exclusão de produtos.
   - RF08: Exclusão de usuários.
   - RF09: Logout.

3. **Requisitos Operacionais**:
   - Acessível via navegadores desktop.
   - Compatível com navegadores modernos.


## Fluxo da Aplicação
A aplicação possui as seguintes telas:
1. **Login:** Validação do e-mail e senha institucional para acesso.
2. **Cadastro:** Registro de novos usuários com validação de dados.
3. **Página Inicial:** Exibição de destaques e ferramenta de busca.
4. **Detalhes do Produto:** Informações detalhadas sobre os anúncios.
5. **Busca:** Resultados baseados na palavra-chave pesquisada.
6. **Perfil do Usuário:** Edição de dados e exclusão de conta.
7. **Avaliação de Vendedores:** Formulário para avaliar transações.
8. **Meus Produtos:** Gerenciamento de anúncios do usuário.

## Como Executar o Projeto
1. Clone o repositório:
   ```bash
   git clone
   Clonar os arquivos no diretorio com o comando git clone
   e criar um banco de dados com base nas especificacoes no arquivo banco.sql
   e por fim criar uma arquivo conexao.php com base nas informacoes abaixo: 

   <?php
$servidor = ""; // Servidor MySQL
$usuario = "";       // Usuário do MySQL
$senha = "";             // Senha do MySQL
$banco = ""; // Nome do banco de dados

// Criar a conexão
$conexao = new mysqli($servidor, $usuario, $senha, $banco);

// Verificar a conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}


?> 
## Como ficou o Projeto
Observar a pasta screenshots da aplicacao para ver imagens da aplicacao;      


