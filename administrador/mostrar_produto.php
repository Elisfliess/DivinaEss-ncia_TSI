<?php
session_start();
require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

if (!isset($_GET['id_produto'])) {
    echo "<p style='color:red;'>ID do produto não fornecido.</p>";
    exit();
}

$id_produto = $_GET['id_produto'];

try {
    $stmt = $pdo->prepare("SELECT * FROM produto WHERE id_produto = ?");
    $stmt->execute([$id_produto]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo "<p style='color:red;'>Produto não encontrado.</p>";
        exit();
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao buscar produto: " . $e->getMessage() . "</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Produto</title>
     <link rel="stylesheet" href="../css/menu.css">

    <style>
body {
    margin: 0;
    padding: 60px 0; /* espaçamento superior e inferior */
    font-family: Arial, sans-serif;
    background: linear-gradient(to right, rgb(253 249 255), rgb(232 214 241));
    display: flex;
    justify-content: center;
}

.container {
    background: white;
    padding: 30px 40px;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    max-width: 500px;
    width: 100%;
}


        h2 {
           text-align: center;
            margin-bottom: 20px;
            color: #4b2d5c;
        }

        .info {
            margin: 10px 0;
            color:rgb(10, 4, 14);
        }

        .label {
            font-weight: bold;
        }

     

/* Isso afeta APENAS os links dentro do .container */
.container a {
  display: inline-block;
  margin-top: 20px;
  text-decoration: none;
  padding: 8px 16px;
  background-color: rgb(176, 121, 212);
  color: white;
  border-radius: 5px;
}

.container a:hover {
    background-color:rgb(160, 117, 209);
}

        img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-top: 10px;
            color: #4b2d5c;
        }
    </style>
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

    
    <div class="container">
        <h2>Detalhes do Produto</h2>

        <div class="info"><span class="label">ID:</span> <?php echo $produto['id_produto']; ?></div>
        <div class="info"><span class="label">Nome:</span> <?php echo $produto['nome_produto']; ?></div>
        
        <div class="info">
            <span class="label">Imagem:</span><br>
            <?php if (!empty($produto['imagem'])): ?>
                <img src="<?php echo $produto['imagem']; ?>" alt="Imagem do Produto">
            <?php else: ?>
                <span style="color:gray;">Sem imagem disponível</span>
            <?php endif; ?>
        </div>

        <div class="info"><span class="label">Fornecedor (ID):</span> <?php echo $produto['id_fornecedor']; ?></div>
        <div class="info"><span class="label">Descrição:</span> <?php echo $produto['descricao']; ?></div>
        <div class="info"><span class="label">Subcategoria (ID):</span> <?php echo $produto['id_sub']; ?></div>
        <div class="info"><span class="label">Estoque:</span> <?php echo $produto['estoque']; ?> unidades</div>
        <div class="info"><span class="label">Preço:</span> R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>

        <div style="text-align: center;">
        <a href="listar_produtos.php">Voltar à Lista</a>
                </div>

    </div>
</body>
</html>
