<?php
// Inicia a sessão PHP para gerenciar o estado do usuário.
session_start();

// Inclui o arquivo de conexão com o banco de dados Azure.
// É crucial que 'conexao_azure.php' esteja no caminho correto e funcione.
require_once('conexao_azure.php');

// Verifica se a variável de sessão 'admin_logado' está definida.
// Se não estiver, o usuário não está logado como administrador e é redirecionado para a página de login.
if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit(); // Garante que o script pare de executar após o redirecionamento.
}

// Inicializa a variável $categorias como um array vazio.
// Isso evita erros caso a consulta ao banco de dados não retorne resultados.
$categorias = []; 

// Bloco try-catch para lidar com possíveis erros na conexão ou consulta ao banco de dados.
try {
    // Prepara a consulta SQL para selecionar todas as categorias da tabela 'categoria'.
    $stmt = $pdo->prepare("SELECT * FROM categoria"); 
    // Executa a consulta preparada.
    $stmt->execute(); 
    // Busca todos os resultados da consulta e os armazena no array $categorias como um array associativo.
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Em caso de erro, exibe uma mensagem de erro em vermelho.
    echo "<p style='color:red;'>Erro ao listar categorias: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Listar Categorias</title>
    <link rel="stylesheet" href="../css/menu.css">
    <style> 
    body { 
        font-family: 'Arial', sans-serif; /* Define a fonte padrão para o corpo da página. */
         background:linear-gradient(to right, rgb(253 249 255), rgb(232 214 241));
    }

     h2 {
            /* Padding e margem para o título da página. */
            padding-left: 165px;
            margin: 20px 0 20px 117px; /* Margem mais controlada para o título. */
        }

    /* Estiliza a tabela. */
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




    /* Estiliza os cabeçalhos (th) e as células (td) da tabela. */
    th, td { 
        padding: 10px; /* Adiciona preenchimento interno de 10px nas células. */
        border: 1px solid #ddd; /* Adiciona uma borda fina e cinza às células. */
        text-align: left; /* Alinha o texto à esquerda. */
    } 

    /* Estiliza os cabeçalhos da tabela. */
    th { 
        background-color: #9E7FAF; /* Cor de fundo roxa. */
        color: white; /* Cor do texto branca. */
    } 

    /* Efeito de hover nas linhas da tabela. */
    tr:hover { 
        background-color: #f1f1f1; /* Muda a cor de fundo da linha ao passar o mouse. */
    } 

    /* Estilo base para botões de ação. */
    .action-btn {
            background-color: #4CAF50;
            color: white;
            padding: 5px 10px;
            border: none;
            text-decoration: none; /* Remove sublinhado dos links. */
            display: inline-block; /* Permite definir padding e margem. */
            border-radius: 3px; /* Pequeno arredondamento para os botões de ação. */
        }

    /* Efeito de hover para botões de ação. */
    .action-btn:hover { 
        background-color: #45a049; /* Escurece a cor de fundo no hover. */
    } 

    /* Estilo específico para o botão de deletar. */
    .delete-btn { 
        background-color: #f44336; /* Cor de fundo vermelha. */
    } 

    /* Efeito de hover para o botão de deletar. */
    .delete-btn:hover { 
        background-color: #da190b; /* Escurece a cor de fundo no hover. */
    } 

    /* Estiliza o contêiner dos botões na parte inferior da página. */
    .botoes-linha {
        display: flex; /* Usa flexbox para organizar os botões. */
        gap: 10px; /* Espaço entre os botões. */
        margin-top: 20px; /* Margem superior. */
        justify-content: center; /* Centraliza os botões horizontalmente. */
        flex-wrap: wrap; /* Permite que os botões quebrem a linha se não houver espaço suficiente. */
    }

    /* Estilo para links que atuam como botões. */
    .botao-link {
        display: inline-block; /* Permite definir largura/altura e margem. */
        padding: 10px 20px; /* Preenchimento interno. */
        margin: 5px 0; /* Margem superior/inferior. */
        background-color: #9E7FAF; /* Cor de fundo roxa. */
        color: white; /* Cor do texto branca. */
        text-decoration: none; /* Remove sublinhado. */
        border: none; /* Remove borda. */
        border-radius: 5px; /* Arredonda as bordas. */
        text-align: center; /* Centraliza o texto. */
        cursor: pointer; /* Muda o cursor para indicar que é clicável. */
    }

    /* Efeito de hover para links que atuam como botões. */
    .botao-link:hover {
        background-color: #7e5f90; /* Escurece a cor de fundo no hover. */
    }
    </style> 

    <script>
    function confirmDeletion() {
        return confirm('Tem certeza que deseja deletar esta categoria?'); /* Alerta de confirmação. */
    }
    </script>

</head>
<body>

    <button class="menu-btn" aria-label="Abrir menu" aria-expanded="false">&#9776;</button>
        
    <div class="hamburguer">
        <img class="logo" src="../img/Logo.png" alt="Logo">
        <nav class="nav">
            <ul>
                <li class="category"><a href="#">ADMINISTRADOR</a>
                    <ul class="submenu">
                        <li><a href="./listar_administrador.php">LISTAR</a></li>
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
        const menuBtn = document.querySelector('.menu-btn'); // Seleciona o botão do menu.
        const hamburguer = document.querySelector('.hamburguer'); // Seleciona o contêiner do menu.
        const categories = document.querySelectorAll(".category"); // Seleciona todas as categorias do menu.

        // Event listener para alternar a visibilidade do menu hambúrguer.
        menuBtn.addEventListener("click", (event) => {
            hamburguer.classList.toggle("active"); // Adiciona/remove a classe 'active' para mostrar/esconder o menu.
            event.stopPropagation(); // Impede a propagação do evento para fechar o menu ao clicar fora.

            const isExpanded = hamburguer.classList.contains("active"); // Verifica se o menu está expandido.
            menuBtn.setAttribute("aria-expanded", isExpanded); // Atualiza o atributo aria-expanded para acessibilidade.
            menuBtn.innerHTML = isExpanded ? "✖" : "&#9776;"; // Altera o ícone do botão.
        });

        // Event listener para cada categoria para alternar a visibilidade dos submenus.
        categories.forEach(category => {
            category.addEventListener("click", (event) => {
                event.stopPropagation(); // Impede a propagação para evitar fechar o menu principal.

                const submenu = category.querySelector(".submenu"); // Seleciona o submenu da categoria.
                const isActive = category.classList.contains("active"); // Verifica se a categoria está ativa.

                // Fecha todos os submenus antes de abrir o clicado.
                categories.forEach(cat => {
                    cat.classList.remove("active");
                    const sm = cat.querySelector(".submenu");
                    if (sm) {
                        sm.style.maxHeight = "0"; // Fecha o submenu.
                        sm.style.opacity = "0"; // Torna o submenu transparente.
                    }
                });

                // Se a categoria não estava ativa e tem um submenu, abre-o.
                if (!isActive && submenu) {
                    category.classList.add("active"); // Ativa a categoria.
                    submenu.style.maxHeight = "500px"; // Define uma altura máxima para o submenu.
                    submenu.style.opacity = "1"; // Torna o submenu visível.
                }
            });
        });

        // Fecha o menu e submenus ao clicar fora.
        document.addEventListener("click", (event) => {
            // Verifica se o clique não foi dentro do menu hambúrguer nem no botão do menu.
            if (!hamburguer.contains(event.target) && !menuBtn.contains(event.target)) {
                hamburguer.classList.remove("active"); // Esconde o menu principal.
                menuBtn.setAttribute("aria-expanded", "false"); // Atualiza o atributo aria-expanded.
                menuBtn.innerHTML = "&#9776;"; // Restaura o ícone do botão.

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

    <h2>Categorias Cadastradas</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
        <?php foreach($categorias as $categ): ?>
        <tr>
            <td><?php echo $categ['id_categoria']; ?></td>
            <td><?php echo $categ['nome']; ?></td>
            
            <td>
                <a href="editar_categorias.php?id_categoria=<?php echo $categ['id_categoria']; ?>" class="action-btn">Editar</a>
                <a href="excluir_categorias.php?id=<?php echo $categ['id_categoria']; ?>" class="action-btn delete-btn" onclick="return confirmDeletion();">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <div class="botoes-linha">
        <a href="exportar_excel.php?tipo=categoria" target="_blank" class="botao-link">Exportar Categorias</a>
        <a href="cadastrar_categorias.php" class="botao-link">Cadastrar Nova Categoria</a>
        <a href="painel_admin.php" class="botao-link">Voltar ao Painel do Administrador</a>
    </div>
</body>
</html>