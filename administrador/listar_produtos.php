<?php
session_start();
require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

$produtos = [];

try {
    $stmt = $pdo->prepare("SELECT * FROM produto");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao listar produtos: " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Listar Produtos</title>
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/listar_produtos.css">
    
 

    <script>
        function confirmDeletion() {
            return confirm('Tem certeza que deseja deletar este produto?');
        }

        function scrollToBottom() {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }

        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
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
        const menuBtn = document.querySelector('.menu-btn');
        const hamburguer = document.querySelector('.hamburguer');
        const categories = document.querySelectorAll(".category");

        menuBtn.addEventListener("click", (event) => {
            hamburguer.classList.toggle("active");
            event.stopPropagation();
            const isExpanded = hamburguer.classList.contains("active");
            menuBtn.setAttribute("aria-expanded", isExpanded);
            menuBtn.innerHTML = isExpanded ? "✖" : "&#9776;";
        });

        categories.forEach(category => {
            category.addEventListener("click", (event) => {
                event.stopPropagation();
                const submenu = category.querySelector(".submenu");
                const isActive = category.classList.contains("active");

                categories.forEach(cat => {
                    cat.classList.remove("active");
                    const sm = cat.querySelector(".submenu");
                    if (sm) {
                        sm.style.maxHeight = "0";
                        sm.style.opacity = "0";
                    }
                });

                if (!isActive && submenu) {
                    category.classList.add("active");
                    submenu.style.maxHeight = "500px";
                    submenu.style.opacity = "1";
                }
            });
        });

        document.addEventListener("click", (event) => {
            if (!hamburguer.contains(event.target) && !menuBtn.contains(event.target)) {
                hamburguer.classList.remove("active");
                menuBtn.setAttribute("aria-expanded", "false");
                menuBtn.innerHTML = "&#9776;";
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

    <h2>Produtos Cadastrados</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Imagem</th>
            <th>Fornecedor</th>
            <th>Descrição</th>
            <th>Subcategoria</th>
            <th>Estoque</th>
            <th>Preço</th>
            <th>Ações</th>
        </tr>
        <?php foreach($produtos as $prod): ?>
        <tr>
            <td><?php echo $prod['id_produto']; ?></td>
            <td><?php echo $prod['nome_produto']; ?></td>
            <td>
                <?php if (!empty($prod['imagem'])): ?>
                    <img src="<?php echo $prod['imagem']; ?>" alt="Imagem do Produto">
                <?php else: ?>
                    Sem imagem
                <?php endif; ?>
            </td>
            <td><?php echo $prod['id_fornecedor']; ?></td>
            <td><?php echo $prod['descricao']; ?></td>
            <td><?php echo $prod['id_sub']; ?></td>
            <td><?php echo $prod['estoque'] . ' unidades'; ?></td>
            <td><?php echo 'R$ ' . number_format($prod['preco'], 2, ',', '.'); ?></td>
            <td>
                <a href="mostrar_produto.php?id_produto=<?php echo $prod['id_produto']; ?>" class="action-btn show-btn">Mostrar</a>
                <a href="editar_produtos.php?id_produto=<?php echo $prod['id_produto']; ?>" class="action-btn">Editar</a>
                <a href="excluir_produtos.php?id_produto=<?php echo $prod['id_produto']; ?>" class="action-btn delete-btn" onclick="return confirmDeletion();">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="botoes-linha">
        <a href="exportar_excel.php?tipo=produto" target="_blank" class="botao-link">Exportar Produtos</a>
        <a href="cadastrar_produtos.php" class="botao-link">Cadastrar Novo Produto</a>
        <a href="painel_admin.php" class="botao-link">Voltar ao Painel do Administrador</a>
    </div>

    <!-- Botões fixos para rolar -->
    <button onclick="scrollToTop()" id="scrollToTopBtn" class="scroll-btn" title="Ir para o topo">⬆</button>
    <button onclick="scrollToBottom()" id="scrollToBottomBtn" class="scroll-btn" title="Ir para o final">⬇</button>

</body>
</html>
