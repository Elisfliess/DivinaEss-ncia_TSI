<?php

// Inicia a sessão PHP.
// É crucial para gerenciar o estado do usuário, como o login.
session_start();

// Inclui o arquivo de conexão com o banco de dados Azure.
// Certifique-se de que 'conexao_azure.php' está no caminho correto e contém as credenciais de conexão PDO.
// Em um ambiente de produção, é recomendável usar caminhos absolutos ou constantes para maior robustez.
require_once('conexao_azure.php');

// Verifica se a sessão 'admin_logado' não está definida.
// Se o administrador não estiver logado, ele é redirecionado para a página de login.
// Isso garante que apenas usuários autenticados possam acessar esta página.
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit(); // Encerra a execução do script para evitar que o restante do código seja processado.
}

// Inicializa um array vazio para armazenar os dados dos administradores.
// Isso evita erros caso a consulta ao banco de dados não retorne resultados.
$administradores = [];

// Bloco try-catch para tratamento de erros na interação com o banco de dados.
try {
    // Prepara a consulta SQL para selecionar ID, nome, email e status ativo dos administradores.
    // IMPORTANTE: A coluna 'adm_senha' FOI REMOVIDA da seleção por questões de segurança.
    // Senhas nunca devem ser recuperadas ou exibidas em texto puro.
    $stmt = $pdo->prepare("SELECT id_administrador, adm_nome, adm_email, adm_ativo FROM administrador");
    
    // Executa a consulta preparada.
    // O uso de prepared statements (prepare() e execute()) previne ataques de SQL Injection.
    $stmt->execute();
    
    // Recupera todos os resultados da consulta como um array associativo.
    // PDO::FETCH_ASSOC retorna cada linha como um array onde as chaves são os nomes das colunas.
    $administradores = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Em caso de erro na conexão ou consulta ao banco de dados:

    // Em ambiente de produção:
    // É uma boa prática registrar o erro em um arquivo de log (ex: error_log("Mensagem de erro...", 0);)
    // e exibir uma mensagem genérica para o usuário, sem expor detalhes técnicos do sistema.
    error_log("Erro ao listar administradores: " . $e->getMessage(), 0); // Registra o erro no log do servidor.
    echo "<p style='color:red;'>Ocorreu um erro ao listar os administradores. Por favor, tente novamente mais tarde.</p>";

    // Em ambiente de desenvolvimento (para depuração):
    // Você pode exibir o erro completo. No entanto, REMOVA ISSO EM PRODUÇÃO!
    // echo "<p style='color:red;'>Erro ao listar administradores: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Administradores</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="icon" type="image/png" href="../img/logo.png">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            /* Gradiente de fundo suave para o corpo da página. */
            background: linear-gradient(to right, rgb(253 249 255), rgb(232 214 241));
        }

        h2 {
            /* Padding e margem para o título da página. */
            padding-left: 165px;
            margin: 20px 0 20px 117px; /* Margem mais controlada para o título. */
        }

        table {
            width: 100%;
            /* 'border-collapse: separate;' é essencial para que 'border-radius' funcione na tabela. */
            border-collapse: separate;
            /* Remove o espaçamento padrão entre as células da tabela. */
            border-spacing: 0;
            margin-top: 20px;
            margin: 20px auto; /* Centraliza a tabela e define margens verticais. */
            max-width: 800px; /* Define uma largura máxima para a tabela. */
            border-radius: 10px; /* **APLICA BORDAS ARREDONDADAS À TABELA.** */
            /* Garante que o conteúdo e o fundo não 'vazem' para fora das bordas arredondadas. */
            overflow: hidden;
            background-color:white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);

        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd; /* Borda fina para as células. */
            text-align: left; /* Alinhamento do texto à esquerda. */
        }

        th {
            background-color: #9E7FAF; /* Cor de fundo para o cabeçalho da tabela. */
            color: white; /* Cor do texto no cabeçalho. */
        }

        tr:hover {
            background-color: #f1f1f1; /* Cor de fundo ao passar o mouse sobre as linhas da tabela. */
        }

        .action-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            text-decoration: none; /* Remove sublinhado dos links. */
            display: inline-block; /* Permite definir padding e margem. */
            border-radius: 3px; /* Pequeno arredondamento para os botões de ação. */
        }

        .action-btn:hover {
            background-color: #45a049; /* Cor de fundo ao passar o mouse. */
        }

        .delete-btn {
            background-color: #f44336; /* Cor específica para o botão de exclusão. */
        }

        .delete-btn:hover {
            background-color: #da190b; /* Cor de fundo ao passar o mouse no botão de exclusão. */
        }

        .botoes-linha {
            display: flex; /* Usa flexbox para organizar os botões em linha. */
            gap: 10px; /* Espaçamento entre os botões. */
            margin-top: 20px;
            justify-content: center; /* Centraliza os botões horizontalmente. */
            flex-wrap: wrap; /* Permite que os botões quebrem a linha em telas pequenas. */
            padding: 0 10px; /* Adiciona um pequeno padding para telas menores, evitando que os botões toquem as bordas. */
        }

        .botao-link {
            display: inline-block;
            padding: 10px 20px;
            margin: 5px 0;
            background-color: #9E7FAF;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px; /* Bordas arredondadas para os botões. */
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease; /* Transição suave para o efeito hover. */
        }

        .botao-link:hover {
            background-color: #7e5f90; /* Cor de fundo ao passar o mouse nos botões. */
        }
    </style>

    <script>
        // Função que exibe uma caixa de diálogo de confirmação antes de excluir um item.
        function confirmDeletion() {
            return confirm('Tem certeza que deseja deletar este administrador? Esta ação não pode ser desfeita.');
        }
    </script>
</head>

<body>

    <button class="menu-btn" aria-label="Abrir menu" aria-expanded="false">&#9776;</button>

    <div class="hamburguer">
        <img class="logo" src="../img/Logo.png" alt="Logo">
        <nav class="nav">
            <ul>
                <li class="category">
                    <a href="#" aria-current="page">ADMINISTRADOR</a>
                    <ul class="submenu">
                        <li><a href="./listar_administrador.php" aria-current="page">LISTAR</a></li>
                        <li><a href="./cadastrar_administrador.php">CADASTRAR</a></li>
                    </ul>
                </li>
                <li class="category"><a href="#">CATEGORIA</a>
                    <ul class="submenu">
                        <li><a href="listar_categorias.php">LISTAR</a></li>
                        <li><a href="./cadastrar_categorias.php">CADASTRAR</a></li>
                    </ul>
                </li>
                <li class="category"><a href="#">FORNECEDOR</a>
                    <ul class="submenu">
                        <li><a href="listar_fornecedores.php">LISTAR</a></li>
                        <li><a href="./cadastrar_fornecedores.php">CADASTRAR</a></li>
                    </ul>
                </li>
                <li class="category"><a href="#">PRODUTO</a>
                    <ul class="submenu">
                        <li><a href="listar_produtos.php">LISTAR</a></li>
                        <li><a href="./cadastrar_produtos.php">CADASTRAR</a></li>
                    </ul>
                </li>
                <li class="category"><a href="#">SUBCATEGORIA</a>
                    <ul class="submenu">
                        <li><a href="listar_subcategorias.php">LISTAR</a></li>
                        <li><a href="./cadastrar_subcategorias.php">CADASTRAR</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const menuBtn = document.querySelector('.menu-btn'); // Botão do menu.
            const hamburguer = document.querySelector('.hamburguer'); // O container do menu lateral.
            const categories = document.querySelectorAll(".category"); // Todas as categorias do menu.

            // Event listener para o botão do menu hambúrguer (abre/fecha o menu lateral).
            menuBtn.addEventListener("click", (event) => {
                hamburguer.classList.toggle("active"); // Adiciona/remove a classe 'active' para mostrar/esconder.
                event.stopPropagation(); // Impede que o clique se propague e feche o menu imediatamente.

                // Atualiza o estado de 'aria-expanded' para acessibilidade e muda o ícone do botão.
                const isExpanded = hamburguer.classList.contains("active");
                menuBtn.setAttribute("aria-expanded", isExpanded);
                menuBtn.innerHTML = isExpanded ? "✖" : "&#9776;"; // Altera entre ícone X e hambúrguer.
            });

            // Event listener para cada categoria (abre/fecha o submenu).
            categories.forEach(category => {
                category.addEventListener("click", (event) => {
                    event.stopPropagation(); // Impede que o clique se propague.

                    const submenu = category.querySelector(".submenu"); // O submenu dentro da categoria clicada.
                    const isActive = category.classList.contains("active"); // Verifica se a categoria já está ativa.

                    // Fecha todos os submenus antes de abrir um novo.
                    categories.forEach(cat => {
                        cat.classList.remove("active");
                        const sm = cat.querySelector(".submenu");
                        if (sm) {
                            sm.style.maxHeight = "0"; // Esconde o submenu.
                            sm.style.opacity = "0"; // Torna o submenu transparente.
                        }
                    });

                    // Se a categoria não estava ativa e tem um submenu, abre-o.
                    if (!isActive && submenu) {
                        category.classList.add("active");
                        submenu.style.maxHeight = "500px"; // Define uma altura máxima para exibir o submenu.
                        submenu.style.opacity = "1"; // Torna o submenu visível.
                    }
                });
            });

            // Event listener para fechar o menu e submenus ao clicar fora deles.
            document.addEventListener("click", (event) => {
                // Verifica se o clique não foi dentro do menu hambúrguer nem no botão do menu.
                if (!hamburguer.contains(event.target) && !menuBtn.contains(event.target)) {
                    hamburguer.classList.remove("active"); // Fecha o menu principal.
                    menuBtn.setAttribute("aria-expanded", "false");
                    menuBtn.innerHTML = "&#9776;"; // Volta ao ícone de hambúrguer.

                    // Fecha todos os submenus.
                    categories.forEach(category => {
                        const submenu = category.querySelector(".submenu");
                        if (submenu) {
                            submenu.style.maxHeight = "0";
                            submenu.style.opacity = "0";
                            category.classList.remove("active");
                        }
                    });
                }
            });
        });
    </script>

    <h2>Administradores Cadastrados</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        <?php if (!empty($administradores)): ?>
        <?php foreach ($administradores as $adm): ?>
        <tr>
            <td><?php echo htmlspecialchars($adm['id_administrador']); ?></td>
            <td><?php echo htmlspecialchars($adm['adm_nome']); ?></td>
            <td><?php echo htmlspecialchars($adm['adm_email']); ?></td>
            <td><?php echo ($adm['adm_ativo'] == 1 ? 'Ativo' : 'Inativo'); ?></td>
            <td>
                <a href="editar_administrador.php?id_administrador=<?php echo htmlspecialchars($adm['id_administrador']); ?>"
                    class="action-btn">Editar</a>
                <a href="excluir_administrador.php?id=<?php echo htmlspecialchars($adm['id_administrador']); ?>"
                    class="action-btn delete-btn" onclick="return confirmDeletion();">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
            <td colspan="5">Nenhum administrador encontrado.</td>
        </tr>
        <?php endif; ?>
    </table>

    <div class="botoes-linha">
        <a href="exportar_excel.php?tipo=administrador" target="_blank" class="botao-link">Exportar Administradores</a>
        <a href="cadastrar_administrador.php" class="botao-link">Cadastrar Novo Administrador</a>
        <a href="painel_admin.php" class="botao-link">Voltar ao Painel do Administrador</a>
    </div>

</body>
</html>