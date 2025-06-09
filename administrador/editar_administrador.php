<?php
session_start();
require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

$mensagem = "";

if (!isset($_GET['id_administrador'])) {
    $mensagem = "<p class='mensagem erro'>ID do administrador não especificado.</p>";
} else {
    $id = $_GET['id_administrador'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM administrador WHERE id_administrador = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $administrador = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$administrador) {
            $mensagem = "<p class='mensagem erro'>Administrador não encontrado.</p>";
        }
    } catch (PDOException $e) {
        $mensagem = "<p class='mensagem erro'>Erro ao buscar administrador: " . $e->getMessage() . "</p>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['adm_nome']);
    $email = trim($_POST['adm_email']);
    $senha = $_POST['senha'];
    $ativo = isset($_POST['adm_ativo']) ? 1 : 0;

    try {
        // Verifica se já existe outro administrador com mesmo nome ou email (exceto o atual)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM administrador WHERE (adm_nome = :nome OR adm_email = :email) AND id_administrador != :id");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $existe = $stmt->fetchColumn();

        if ($existe > 0) {
            $mensagem = "<p class='mensagem erro'>Já existe um administrador com este nome ou e-mail.</p>";
        } else {
            // Atualiza dados do administrador
            $stmt = $pdo->prepare("UPDATE administrador SET adm_nome = :nome, adm_email = :email, adm_senha = :senha, adm_ativo = :ativo WHERE id_administrador = :id");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Atualiza $administrador para manter os dados no formulário após a edição
            $administrador['adm_nome'] = $nome;
            $administrador['adm_email'] = $email;
            $administrador['adm_senha'] = $senha;
            $administrador['adm_ativo'] = $ativo;

            $mensagem = "<p class='mensagem sucesso'>Administrador atualizado com sucesso!</p>";
        }
    } catch (PDOException $e) {
        $mensagem = "<p class='mensagem erro'>Erro ao atualizar administrador: " . $e->getMessage() . "</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Administrador</title>
    <link rel="stylesheet" href="../css/menu.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
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
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
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

        .input-group {
            position: relative;
        }

        .eye {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #333;
            font-size: 18px;
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
            text-align: center;
            width: 100%;
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

                
        .input-group {
            display: flex;
            position: relative;
            width: 500px;
            margin-bottom: 15px; /* espaçamento inferior, opcional */
        }

        .input-group input {
            display: flex;
            width:192%;
            height: 35px;
            padding: 8px 40px 8px 10px; /* espaço interno (padding) ajustado para o ícone */
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .input-group .eye {
            display: flex;
            position: absolute;
            top: 40%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #333;
            font-size: 18px;
            transition: color 0.2s;
        }

        .input-group .eye:hover {
            color: #000;
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




<!-- Formulário -->
<div class="container">
    <h2>Editar Administrador</h2>
    <?php if (isset($mensagem)) echo $mensagem; ?>

    <?php if (isset($administrador)) : ?>
        <form method="post">
            <label for="adm_nome">Nome:</label>
            <input type="text" name="adm_nome" id="adm_nome" value="<?php echo htmlspecialchars($administrador['adm_nome']); ?>" required>

            <label for="adm_email">Email:</label>
            <input type="email" name="adm_email" id="adm_email" value="<?php echo htmlspecialchars($administrador['adm_email']); ?>" required>

            <label for="adm_senha">Senha:</label>
        <div class="input-group">
            <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
            <span class="eye" onclick="alternarSenha()">
                <i class="fa-solid fa-eye" id="iconeOlho"></i>
            </span>
        </div>

            <label for="adm_ativo">Ativo:</label>
            <input type="checkbox" name="adm_ativo" id="adm_ativo" <?php if ($administrador['adm_ativo'] == 1) echo 'checked'; ?>>

            <br><br>
            <button type="submit">Salvar Alterações</button>
        </form>

        <div style="text-align: center;">
            <a href="listar_administrador.php">Voltar para a listagem</a>
        </div>
    <?php endif; ?>
</div>

<!-- Script para alternar visualização da senha -->
<script>
function alternarSenha() {
    const senhaInput = document.getElementById("senha");
    const icone = document.getElementById("iconeOlho");

    if (senhaInput.type === "password") {
        senhaInput.type = "text";
        icone.classList.remove("fa-eye");
        icone.classList.add("fa-eye-slash");
    } else {
        senhaInput.type = "password";
        icone.classList.remove("fa-eye-slash");
        icone.classList.add("fa-eye");
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</body>
</html>
