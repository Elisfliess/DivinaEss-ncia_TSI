<?php
session_start();
require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

$mensagem = '';

// Verifica se o ID do fornecedor foi passado
if (!isset($_GET['id_fornecedor'])) {
    $mensagem = "<p class='mensagem erro'>ID do fornecedor não especificado.</p>";
} else {
    $id = $_GET['id_fornecedor'];

    try {
        // Busca o fornecedor pelo ID
        $stmt = $pdo->prepare("SELECT * FROM fornecedor WHERE id_fornecedor = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fornecedor) {
            $mensagem = "<p class='mensagem erro'>Fornecedor não encontrado.</p>";
        }

    } catch (PDOException $e) {
        $mensagem = "<p class='mensagem erro'>Erro ao buscar fornecedor: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($fornecedor)) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $cnpj = trim($_POST['cnpj']);
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);

    try {
        // Verifica se já existe outro fornecedor com o mesmo nome ou CNPJ (excluindo o atual)
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM fornecedor WHERE (nome = :nome OR cnpj = :cnpj) AND id_fornecedor != :id");
        $stmt_check->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt_check->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
        $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_check->execute();
        $existe = $stmt_check->fetchColumn();

        if ($existe > 0) {
            $mensagem = "<p class='mensagem erro'>Erro: Já existe um fornecedor com esse nome ou CNPJ.</p>";
        } else {
            // Atualiza o fornecedor
            $stmt = $pdo->prepare("UPDATE fornecedor SET nome = :nome, email = :email, cnpj = :cnpj, endereco = :endereco, telefone = :telefone WHERE id_fornecedor = :id");
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':cnpj', $cnpj, PDO::PARAM_STR);
            $stmt->bindParam(':endereco', $endereco, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $mensagem = "<p class='mensagem sucesso'>Fornecedor atualizado com sucesso!</p>";

            // Atualiza os dados do fornecedor para exibir no formulário após atualização
            $fornecedor['nome'] = $nome;
            $fornecedor['email'] = $email;
            $fornecedor['cnpj'] = $cnpj;
            $fornecedor['endereco'] = $endereco;
            $fornecedor['telefone'] = $telefone;
        }

    } catch (PDOException $e) {
        $mensagem = "<p class='mensagem erro'>Erro ao atualizar fornecedor: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Fornecedor</title>
    <link rel="stylesheet" href="../css/menu.css">
    <style>
        body {
            margin: 0; padding: 0;
            font-family: Arial, sans-serif;
            background:linear-gradient(to right, rgb(253 249 255), rgb(232 214 241));
            height: 100vh;
            display: flex; justify-content: center; align-items: center;
        }

        .container {
            background: white;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
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
            margin-top: 13px;
            font-weight: bold;
            color: #333;
        }


        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            margin-top: 20px;
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
    <h2>Editar Fornecedor</h2>

    <?php if (isset($mensagem)) echo $mensagem; ?>

    <?php if (isset($fornecedor)) : ?>
        <form method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($fornecedor['nome']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($fornecedor['email']); ?>" required>

            <label for="cnpj">CNPJ:</label>
            <input type="text" name="cnpj" id="cnpj" oninput="mascaracnpj(this)" value="<?php echo htmlspecialchars($fornecedor['cnpj']); ?>" required>

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" id="endereco" value="<?php echo htmlspecialchars($fornecedor['endereco']); ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" oninput="mascaratelefone(this)" value="<?php echo htmlspecialchars($fornecedor['telefone']); ?>" required>

            <button type="submit">Salvar Alterações</button>
        </form>

        <div style="text-align: center;">
            <a href="listar_fornecedores.php">Voltar para a listagem</a>
        </div>    <?php endif; ?>
</div>

<script>
    function mascaracnpj(campo) {
        campo.value = campo.value
            .replace(/\D/g, '')
            .replace(/^(\d{2})(\d)/, '$1.$2')
            .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
            .replace(/\.(\d{3})(\d)/, '.$1/$2')
            .replace(/(\d{4})(\d)/, '$1-$2')
            .replace(/(-\d{2})\d+?$/, '$1');
    }

    function mascaratelefone(campo) {
        campo.value = campo.value
            .replace(/\D/g, '')
            .replace(/^(\d{2})(\d)/g, '($1) $2')
            .replace(/(\d{5})(\d)/, '$1-$2')
            .replace(/(-\d{4})\d+?$/, '$1');
    }
</script>
</body>
</html>
