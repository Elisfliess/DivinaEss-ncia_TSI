<?php
session_start();
require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

$mensagem = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);

    if (empty($nome)) {
        $mensagem = "<p class='mensagem erro'>O nome da categoria é obrigatório.</p>";
    } else {
        // Verifica duplicidade
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM categoria WHERE LOWER(nome) = LOWER(:nome)");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            $mensagem = "<p class='mensagem erro'>Esta categoria já está cadastrada.</p>";
        } else {
            try {
                $sql = "INSERT INTO categoria (nome) VALUES (:nome);";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
                $stmt->execute();

                $mensagem = "<p class='mensagem sucesso'>Categoria cadastrada com sucesso!</p>";
            } catch (PDOException $e) {
                $mensagem = "<p class='mensagem erro'>Erro ao cadastrar Categoria: " . $e->getMessage() . "</p>";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Categoria</title>
     <link rel="stylesheet" href="../css/menu.css">
  
</head>
<body>


 <!-- MENU HAMBURGUER -->
    <button class="menu-btn" aria-label="Abrir menu" aria-expanded="false">&#9776;</button>
    
    <!-- Menu suspenso -->
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

    <!-- JavaScript para ativação do menu -->
    <script>
document.addEventListener("DOMContentLoaded", () => {
    const menuBtn = document.querySelector('.menu-btn');
    const hamburguer = document.querySelector('.hamburguer');
    const categories = document.querySelectorAll(".category");

    // Alterna o menu hambúrguer
    menuBtn.addEventListener("click", (event) => {
        hamburguer.classList.toggle("active");
        event.stopPropagation();

        const isExpanded = hamburguer.classList.contains("active");
        menuBtn.setAttribute("aria-expanded", isExpanded);
        menuBtn.innerHTML = isExpanded ? "✖" : "&#9776;";
    });

    // Submenu por categoria
    categories.forEach(category => {
        category.addEventListener("click", (event) => {
            event.stopPropagation();

            const submenu = category.querySelector(".submenu");
            const isActive = category.classList.contains("active");

            // Fecha todos
            categories.forEach(cat => {
                cat.classList.remove("active");
                const sm = cat.querySelector(".submenu");
                if (sm) {
                    sm.style.maxHeight = "0";
                    sm.style.opacity = "0";
                }
            });

            // Se não estava ativa, abre essa
            if (!isActive && submenu) {
                category.classList.add("active");
                submenu.style.maxHeight = "500px";
                submenu.style.opacity = "1";
            }
        });
    });

    // Fecha menu e submenus ao clicar fora
    document.addEventListener("click", (event) => {
        if (!hamburguer.contains(event.target) && !menuBtn.contains(event.target)) {
            hamburguer.classList.remove("active");
            menuBtn.setAttribute("aria-expanded", "false");
            menuBtn.innerHTML = "&#9776;";

            // Fecha todos submenus
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

    </script>

    <!-- Fim menu Hamburguer -->


<div class="cubo">
    <h2>Cadastrar Categoria</h2>
        <?php if (!empty($mensagem)) echo $mensagem; ?>
    <form action="" method="post">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" placeholder="Ex: Sabonetes" required>

        <button type="submit">Cadastrar Categoria</button>

        <div class="links">
            <a href="painel_admin.php">Voltar ao Painel Administrador</a><br>
            <a href="listar_categorias.php">Listar Categorias</a>
        </div>
    </form>
</div>

</body>
</html>
