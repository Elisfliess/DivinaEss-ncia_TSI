<?php
session_start();
require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id_sub'])) {
    echo "<p style='color:red;'>ID da subcategoria não especificado.</p>";
    exit();
}

$id = $_GET['id_sub'];

try {
    $stmt = $pdo->prepare("SELECT * FROM subcategoria WHERE id_sub = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $subcategoria = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$subcategoria) {
        echo "<p style='color:red;'>Subcategoria não encontrada.</p>";
        exit();
    }

} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao buscar subcategoria: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit();
}

$msg = "";  // variável para mensagens

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idcategoria = $_POST['idcategoria'];
    $nome = trim($_POST['nome']);

    try {
        // Verifica se já existe uma subcategoria com esse nome e categoria, excluindo a atual
        $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM subcategoria WHERE nome = :nome AND id_categoria = :idcategoria AND id_sub != :id");
        $check_stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $check_stmt->bindParam(':idcategoria', $idcategoria, PDO::PARAM_INT);
        $check_stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $check_stmt->execute();
        $exists = $check_stmt->fetchColumn();

        if ($exists) {
            $msg = "<p class='mensagem erro'>Já existe uma subcategoria com esse nome nessa categoria.</p>";
        } else {
            $stmt = $pdo->prepare("UPDATE subcategoria SET nome = :nome, id_categoria = :idcategoria WHERE id_sub = :id");
            $stmt->bindParam(':idcategoria', $idcategoria, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $msg = "<p class='mensagem sucesso'>Subcategoria atualizada com sucesso!</p>";

            // Atualiza os dados da subcategoria para exibir no formulário
            $subcategoria['nome'] = $nome;
            $subcategoria['id_categoria'] = $idcategoria;
        }
    } catch (PDOException $e) {
        $msg = "<p class='mensagem erro'>Erro ao atualizar subcategoria: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Subcategoria</title>
    <link rel="stylesheet" href="../css/menu.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #d5c6e0, #f0e6f5);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            max-width: 480px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 28px;
            font-weight: 700;
            color: #4b2d5c;
            font-size: 28px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            font-size: 16px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 14px 16px;
            font-size: 16px;
            border: 1.8px solid #ccc;
            border-radius: 8px;
            margin-bottom: 24px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        select:focus {
            border-color: #9e7faf;
            outline: none;
        }

        button {
            width: 100%;
            padding: 14px 0;
            background-color: #9E7FAF;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #7f6390;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 26px;
            font-weight: 600;
            color: #9E7FAF;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        a:hover {
            color: #7f6390;
        }

        .mensagem {
            text-align: center;
            padding: 14px 20px;
            margin-bottom: 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
        }

        .sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1.5px solid #c3e6cb;
        }

        .erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1.5px solid #f5c6cb;
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

<div class="container">
    <h2>Editar Subcategoria</h2>

    <?php
    if (!empty($msg)) {
        echo $msg;
    }
    ?>

    <form method="post">
        <label for="idcategoria">Categoria:</label>
        <select name="idcategoria" id="idcategoria" required>
            <option value="">Selecione a categoria</option>
            <?php
            $stmt = $pdo->query("SELECT id_categoria, nome FROM categoria");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $selected = ($row['id_categoria'] == $subcategoria['id_categoria']) ? "selected" : "";
                echo "<option value='{$row['id_categoria']}' $selected>{$row['nome']}</option>";
            }
            ?>
        </select>

        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($subcategoria['nome']); ?>" required>

        <button type="submit">Salvar Alterações</button>
    </form>

    <div style="text-align: center;">
        <a href="listar_subcategorias.php">Voltar para a listagem</a>
    </div>
</div>
</body>
</html>
