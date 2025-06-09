<?php
session_start();
require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id_produto'])) {
    echo "<p style='color:red;'>ID do produto não especificado.</p>";
    exit();
}

$id = $_GET['id_produto'];

try {
    $stmt = $pdo->prepare("SELECT * FROM produto WHERE id_produto = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produto) {
        echo "<p style='color:red;'>Produto não encontrado.</p>";
        exit();
    }

} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao buscar produto: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $imagem = $_POST['imagem'];
    $fornecedor = $_POST['fornecedor'];
    $descricao = $_POST['descricao'];
    $subcategoria = $_POST['subcategoria'];
    $estoque = $_POST['estoque'];
    $preco = $_POST['preco'];

    // Verifica se já existe outro produto com o mesmo nome (exceto o atual)
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM produto WHERE nome_produto = :nome AND id_produto != :id");
    $stmt_check->bindParam(':nome', $nome);
    $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt_check->execute();
    $count = $stmt_check->fetchColumn();

    if ($count > 0) {
        // Produto com esse nome já existe
        $mensagem = "Erro: Já existe um produto com esse nome.";
        $tipo_mensagem = "erro";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE produto SET nome_produto = :nome, imagem = :imagem, id_fornecedor = :fornecedor, descricao = :descricao, id_sub = :subcategoria, estoque = :estoque, preco = :preco WHERE id_produto = :id");

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':imagem', $imagem);
            $stmt->bindParam(':fornecedor', $fornecedor, PDO::PARAM_INT);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':subcategoria', $subcategoria, PDO::PARAM_INT);
            $stmt->bindParam(':estoque', $estoque, PDO::PARAM_INT);
            $stmt->bindParam(':preco', $preco);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $mensagem = "Produto atualizado com sucesso!";
            $tipo_mensagem = "sucesso";

            // Atualiza $produto para mostrar os dados novos no formulário
            $produto['nome_produto'] = $nome;
            $produto['imagem'] = $imagem;
            $produto['id_fornecedor'] = $fornecedor;
            $produto['descricao'] = $descricao;
            $produto['id_sub'] = $subcategoria;
            $produto['estoque'] = $estoque;
            $produto['preco'] = $preco;

        } catch (PDOException $e) {
            $mensagem = "Erro ao atualizar produto: " . htmlspecialchars($e->getMessage());
            $tipo_mensagem = "erro";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="../css/menu.css">
    <style>
    body {
        margin: 0; 
        padding: 40px 20px; /* Espaço no topo e rodapé */
        font-family: Arial, sans-serif;
        background: linear-gradient(to right, #d5c6e0, #f0e6f5);
        min-height: 100vh;
        display: flex; 
        justify-content: center; 
        align-items: center;
        box-sizing: border-box;
    }

    .container {
        background: white;
        padding: 30px 40px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        max-width: 600px;
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
    input[type="email"],
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

    input[type="checkbox"] {
        transform: scale(1.2);
        margin-top: 5px;
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

    a {
        display: inline-block;
        margin-top: 15px;
        text-decoration: none;
        color: #9E7FAF;
        font-weight: bold;
        transition: color 0.3s;
    }

    a:hover {
        color: #7f6390;
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
<h2>Editar Produto</h2>
<?php if (isset($mensagem)): ?>
    <div class="mensagem <?php echo $tipo_mensagem; ?>">
        <?php echo htmlspecialchars($mensagem); ?>
    </div>
<?php endif; ?>
<form method="post">

    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($produto['nome_produto']); ?>" required>

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
            $selected = ($row['id_fornecedor'] == $produto['id_fornecedor']) ? 'selected' : '';
            echo "<option value='{$row['id_fornecedor']}' $selected>{$row['nome']}</option>";
        }
        ?>
    </select>

    <label for="descricao">Descrição:</label>
    <input type="text" name="descricao" id="descricao" value="<?php echo isset($produto['descricao']) ? htmlspecialchars($produto['descricao']) : ''; ?>" required>

    <label for="subcategoria">Subcategoria:</label>
    <select name="subcategoria" id="subcategoria" required>
        <option value="">Selecione uma subcategoria</option>
        <?php
        $stmt = $pdo->query("SELECT id_sub, nome FROM subcategoria");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $selected = ($row['id_sub'] == $produto['id_sub']) ? 'selected' : '';
            echo "<option value='{$row['id_sub']}' $selected>{$row['nome']}</option>";
        }
        ?>
    </select>

    <label for="estoque">Estoque:</label>
    <input type="number" name="estoque" id="estoque" value="<?php echo isset($produto['estoque']) ? $produto['estoque'] : ''; ?>" required> unidades

    <label for="preco">Preço:</label>
    <input type="text" name="preco" id="preco" value="<?php echo isset($produto['preco']) ? $produto['preco'] : ''; ?>" oninput="mascaramoeda(this)" placeholder="R$ 00,00" required>

    <button type="submit">Salvar Alterações</button>
</form>


        <div style="text-align: center;">
            <a href="listar_produtos.php">Voltar para a listagem</a>
        </div>

<script>
function mascaramoeda(input) {
    let valor = input.value.replace(/\D/g, '');
    valor = valor.replace(/(\d)(\d{2})$/, '$1,$2');
    valor = valor.replace(/(\d)(\d{3})$/, '$1.$2');
    input.value = valor;
}
</script>
</body>
</html>
