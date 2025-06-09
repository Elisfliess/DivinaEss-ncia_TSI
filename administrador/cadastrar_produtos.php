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
    $imagem = trim($_POST['imagem']);
    $fornecedor = $_POST['fornecedor'];
    $descricao = trim($_POST['descricao']);
    $subcategoria = $_POST['subcategoria'];
    $estoque = $_POST['estoque'];

    $preco_raw = $_POST['preco'];
    $preco_limpo = preg_replace('/[^0-9,]/', '', $preco_raw);
    $preco_formatado = str_replace(',', '.', $preco_limpo);
    $preco = floatval($preco_formatado);

    if ($preco <= 0) {
        $mensagem = "<p class='mensagem erro'>Por favor, informe um valor de preço válido maior que zero.</p>";
    } elseif (empty($nome) || empty($fornecedor) || empty($descricao) || empty($subcategoria) || empty($estoque) || $preco_raw === '') {
        $mensagem = "<p class='mensagem erro'>Todos os campos são obrigatórios.</p>";
    } else {
        // VALIDA FORNECEDOR
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM fornecedor WHERE id_fornecedor = :id");
        $stmt->bindParam(':id', $fornecedor, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetchColumn() == 0) {
            $mensagem = "<p class='mensagem erro'>Fornecedor inválido.</p>";
        } else {
            // VALIDA SUBCATEGORIA
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM subcategoria WHERE id_sub = :id");
            $stmt->bindParam(':id', $subcategoria, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() == 0) {
                $mensagem = "<p class='mensagem erro'>Subcategoria inválida.</p>";
            } else {
                // VERIFICA SE O PRODUTO JÁ EXISTE PELO NOME (ignora case e espaços extras)
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM produto WHERE LOWER(TRIM(nome_produto)) = LOWER(TRIM(:nome))");
                $stmt->bindParam(':nome', $nome);
                $stmt->execute();
                if ($stmt->fetchColumn() > 0) {
                    $mensagem = "<p class='mensagem erro'>Produto já cadastrado com este nome.</p>";
                } else {
                    try {
                        $sql = "INSERT INTO produto (nome_produto, imagem, id_fornecedor, descricao, id_sub, estoque, preco) 
                                VALUES (:nome, :imagem, :fornecedor, :descricao, :subcategoria, :estoque, :preco)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':nome', $nome);
                        $stmt->bindParam(':imagem', $imagem);
                        $stmt->bindParam(':fornecedor', $fornecedor, PDO::PARAM_INT);
                        $stmt->bindParam(':descricao', $descricao);
                        $stmt->bindParam(':subcategoria', $subcategoria, PDO::PARAM_INT);
                        $stmt->bindParam(':estoque', $estoque, PDO::PARAM_INT);
                        $stmt->bindParam(':preco', $preco);
                        $stmt->execute();

                        $produto_id = $pdo->lastInsertId();
                        $mensagem = "<p class='mensagem sucesso'>Produto cadastrado com sucesso! ID: $produto_id</p>";
                    } catch (PDOException $e) {
                        $mensagem = "<p class='mensagem erro'>Erro ao cadastrar Produto: " . $e->getMessage() . "</p>";
                    }
                }
            }
        }
    }
}
?>




<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="../css/menu.css">
   <style>
    body {
        margin: 0;
        padding: 40px 0; /* espaçamento no topo e rodapé */
        font-family: Arial, sans-serif;
        background: linear-gradient(to right, #d5c6e0, #f0e6f5);
        display: flex;
        justify-content: center;
        align-items: flex-start; /* alinha ao topo com o padding */
        min-height: 100vh; /* garante altura total da tela */
        box-sizing: border-box;
        background:linear-gradient(to right, rgb(253 249 255), rgb(232 214 241));
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

    label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
        color: #333;
    }

    input[type="text"],
    input[type="number"],
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 16px;
    }

    button {
        width: 100%;
        padding: 12px;
        background-color: #9E7FAF;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #7f6390;
    }

        .mensagem {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
            padding: 10px;
            border-radius: 6px;
        }

        .sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    a {
        display: inline-block;
        margin-top: 10px;
        text-decoration: none;
        color: #9E7FAF;
        font-weight: bold;
        transition: color 0.3s;
    }

    a:hover {
        color: #7f6390;
    }

    p {
        margin: 0 0 10px;
    }

    .links {
        text-align: center;
        margin-top: 20px;
    }

    .links a {
        color: #9E7FAF;
        text-decoration: none;
        display: inline-block;
        margin: 5px 0;
        font-weight: bold;
    }

    .links a:hover {
        text-decoration: underline;
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
    <h2>Cadastrar Produto</h2>
        <?php if (!empty($mensagem)) echo $mensagem; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>

    <label for="imagem">Imagem:
    <a href="https://freeimage.host/isabellaavelina" target="_blank" rel="noopener noreferrer" style="margin-left: 5px; font-weight: normal; font-size: 0.9em;">Hospedagem de imagens</a>
    </label>
    <input type="text" name="imagem" id="imagem" value="<?php echo isset($produto['imagem']) ? htmlspecialchars($produto['imagem']) : ''; ?>" placeholder="add url" required>

        <label for="fornecedor">Fornecedor:</label>
        <select name="fornecedor" id="fornecedor" required>
            <option value="">Selecione um fornecedor</option>
            <?php
            $stmt = $pdo->query("SELECT id_fornecedor, nome FROM fornecedor");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['id_fornecedor']}'>{$row['nome']}</option>";
            }
            ?>
        </select>

        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" id="descricao" required>

        <label for="subcategoria">Subcategoria:</label>
        <select name="subcategoria" id="subcategoria" required>
            <option value="">Selecione uma subcategoria</option>
            <?php
            $stmt = $pdo->query("SELECT id_sub, nome FROM subcategoria");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['id_sub']}'>{$row['nome']}</option>";
            }
            ?>
        </select>

        <label for="estoque">Estoque:</label>
        <input type="number" name="estoque" id="estoque" placeholder="50 unidades" required min="1">

        <label for="preco">Preço:</label>
        <input type="text" name="preco" id="preco" oninput="mascaramoeda(this)" placeholder="R$ 00,00" required>

        <button type="submit">Cadastrar Produto</button>
    </form>
   <div class="links">
    <p><a href="painel_admin.php">Voltar ao Painel do Administrador</a></p>
    <p><a href="listar_produtos.php">Listar Produtos</a></p>
        </div>
</div>

<script>
    function mascaramoeda(campo) {
        let v = campo.value.replace(/\D/g, '');
        if (v.length === 0) v = '0';
        let valor = (parseInt(v) / 100).toFixed(2);
        campo.value = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(valor);
    }
</script>
</body>
</html>
