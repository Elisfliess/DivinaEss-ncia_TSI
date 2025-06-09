<?php
session_start();
require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']) . '@divina.com';
    $senha = trim($_POST['senha']);
    $ativo = isset($_POST['ativo']) ? 1 : 0;

    $erros = [];

    // Verificações
    if (empty($nome) || empty($_POST['email']) || empty($senha)) {
        $erros[] = "Todos os campos são obrigatórios.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Formato de e-mail inválido.";
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM ADMINISTRADOR WHERE ADM_EMAIL = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        $erros[] = "Este e-mail já está cadastrado.";
    }

    // Exibir erros, se houver
    if (!empty($erros)) {
        $mensagem = '<div class="mensagem erro"><ul>';
        foreach ($erros as $erro) {
            $mensagem .= "<li>$erro</li>";
        }
        $mensagem .= '</ul></div>';
    } else {
        // Tenta cadastrar
        try {
            $sql = "INSERT INTO ADMINISTRADOR (ADM_NOME, ADM_EMAIL, ADM_SENHA, ADM_ATIVO) 
                    VALUES (:nome, :email, :senha, :ativo)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':ativo', $ativo);
            $stmt->execute();

            $adm_id = $pdo->lastInsertId();
            $mensagem = "<p class='mensagem sucesso'>Administrador cadastrado com sucesso! ID: " . $adm_id . "</p>";
        } catch (PDOException $e) {
            $mensagem = "<p class='mensagem erro'>Erro ao cadastrar Administrador: " . $e->getMessage() . "</p>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Administrador</title>
     <link rel="stylesheet" href="../css/menu.css">

    <style>
        body {
            margin: 0; padding: 0;
            font-family: Arial, sans-serif;
            background:linear-gradient(to right, rgb(253 249 255), rgb(232 214 241));
            height: 100vh;
            display: flex; justify-content: center; align-items: center;
        }



        .cubo {
            background: white;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
        }

        .cubo h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #4b2d5c;
        }

        label {
            display: block;
            margin-top: 20px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        input[type="checkbox"] {
            margin-top: 10px;
        }

  
    .cubo button {
    width: 100%;
    background-color: #9E7FAF;
    color: white;
    border: none;
    padding: 12px;
    margin-top: 20px;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
}



        button:hover {
            background-color: #7e5f90;
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
        }

        .links a:hover {
            text-decoration: underline;
        }

        .email-group {
    position: relative;
    display: flex;
    align-items: center;
    width: 519px;
}

.email-group input[type="text"] {
    padding-right: 110px;
    flex: 1;
}

.email-domain {
    position: absolute;
    right: 10px;
    color: #555;
    pointer-events: none;
    font-size: 14px;
}

          
        .input-group {
            display: flex;
            position: relative;
            width: 522px;
            margin-bottom: 15px; /* espaçamento inferior, opcional */
        }

        .input-group input {
            display: flex;
            width:192%;
            height: 35px;
            padding: 8px 40px 8px 10px; /* espaço interno (padding) ajustado para o ícone */
            box-sizing: border-box;
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


<div class="cubo">
    <h2>Cadastrar Administrador</h2>
    <?php if (!empty($mensagem)) echo $mensagem; ?>
    <form action="" method="post">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" placeholder="Fulano da Silva" required>

        <label for="email">Email:</label>
    <div class="email-group">
        <input type="text" name="email" id="email" placeholder="fulano.silva" required>
        <span class="email-domain">@divina.com</span>
    </div>
    <label for="adm_senha">Senha:</label>
         <div class="input-group">
            <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
            <span class="eye" onclick="alternarSenha()">
                <i class="fa-solid fa-eye" id="iconeOlho"></i>
            </span>
        </div>

        <label for="ativo">Ativo:</label>
        <input type="checkbox" name="ativo" id="ativo" value="1" checked>  

        <button type="submit">Cadastrar Administrador</button>
    </form>

    <div class="links">
        <a href="painel_admin.php">Voltar ao Painel Administrador</a><br>
        <a href="listar_administrador.php">Listar Administradores</a>
    </div>
</div>

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

    document.querySelector('form').addEventListener('submit', function(e) {
        const nome = document.getElementById('nome').value.trim();
        const email = document.getElementById('email').value.trim();
        const senha = document.getElementById('senha').value;

        if (!nome || !email || !senha) {
            alert('Todos os campos são obrigatórios!');
            e.preventDefault();
            return;
        }

        if (email.includes('@')) {
            alert('Digite apenas a parte antes do @ no campo de e-mail.');
            e.preventDefault();
            return;
        }

        if (senha.length < 4) {
            alert('A senha deve ter pelo menos 4 caracteres.');
            e.preventDefault();
            return;
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</body>
</html>
