<?php
session_start();
require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $cnpj = trim($_POST['cnpj']);
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);

    // Validação geral
    if (empty($nome) || empty($email) || empty($cnpj) || empty($endereco) || empty($telefone)) {
        $mensagem = "<p class='mensagem erro'>Todos os campos são obrigatórios.</p>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "<p class='mensagem erro'>Formato de e-mail inválido.</p>";
    } elseif (!preg_match('/\d+/', $endereco)) {
        $mensagem = "<p class='mensagem erro'>Endereço inválido. Inclua o número (ex: Rua tal, 123).</p>";
    } elseif (!preg_match('/-/', $endereco)) {
        $mensagem = "<p class='mensagem erro'>Endereço inválido. Certifique-se de incluir '- SP' no final.</p>";
    } else {
        // Verifica se e-mail já existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM fornecedor WHERE LOWER(email) = LOWER(:email)");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $mensagem = "<p class='mensagem erro'>Este e-mail já está cadastrado.</p>";
        } else {
            // Verifica se CNPJ já existe
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM fornecedor WHERE cnpj = :cnpj");
            $stmt->bindParam(':cnpj', $cnpj);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $mensagem = "<p class='mensagem erro'>Este CNPJ já está cadastrado.</p>";
            } else {
                // Inserção segura
                try {
                    $sql = "INSERT INTO fornecedor (nome, email, cnpj, endereco, telefone) 
                            VALUES (:nome, :email, :cnpj, :endereco, :telefone)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':cnpj', $cnpj);
                    $stmt->bindParam(':endereco', $endereco);
                    $stmt->bindParam(':telefone', $telefone);
                    $stmt->execute();

                    $fornecedor_id = $pdo->lastInsertId();
                    $mensagem = "<p class='mensagem sucesso'>Fornecedor cadastrado com sucesso! ID: " . $fornecedor_id . "</p>";
                } catch (PDOException $e) {
                    $mensagem = "<p class='mensagem erro'>Erro ao cadastrar fornecedor: " . $e->getMessage() . "</p>";
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
    <title>Cadastro de Fornecedor</title>
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
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .input-wrapper {
            position: relative;
            width: 100%;
            margin-bottom: 15px;
        }
        .input-wrapper input {
            width: 100%;
            padding-right: 120px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .input-wrapper::after {
            content: "@divina.com";
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #555;
            font-size: 16px;
            pointer-events: none;
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

<h2>Cadastrar Fornecedor</h2>
    <?php if (!empty($mensagem)) echo $mensagem; ?>

<form action="" method="post" enctype="multipart/form-data">
    <label for="nome">Nome:</label>
    <input type="text" name="nome" id="nome" placeholder="Divina Essência" required>
    <p>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" placeholder="Divinaessência@fornece.com.br" required><br>
    <p>

    <label for="cnpj">CNPJ:</label>
    <input type="text" name="cnpj" id="cnpj" oninput="mascaracnpj(this)" placeholder="00.000.000/0000-00" required><br>
    <p>

    <label for="endereco">Endereço:</label>
    <input type="text" name="endereco" id="endereco"7 placeholder="Rua tal, 123 - SP" required><br>
    <p>

    <label for="telefone">Telefone:</label>
    <input type="text" name="telefone" id="telefone" oninput="mascaratelefone(this)" placeholder="(00) 00000-0000" required><br>

    <p>
    <button type="submit">Cadastrar Fornecedor</button>
    <p></p>
    <div class="links">
    <a href="painel_admin.php">Voltar ao Painel do Administrador</a><br>
    <a href="listar_fornecedores.php">Listar Fornecedor</a>
 </div>
</form>
</div>


<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const endereco = document.getElementById('endereco').value.trim();
    const temNumero = /\d/.test(endereco);
    if (!temNumero) {
        alert('Por favor, inclua o número no campo de endereço.');
        e.preventDefault();
    }
});
function mascaracnpj(campo) {
      let v = campo.value.replaceAll('.', '').replaceAll('/', '').replaceAll('-', '');
      if (v.length > 2) v = v.slice(0, 2) + '.' + v.slice(2);
      if (v.length > 6) v = v.slice(0, 6) + '.' + v.slice(6);
      if (v.length > 10) v = v.slice(0, 10) + '/' + v.slice(10);
      if (v.length > 15) v = v.slice(0, 15) + '-' + v.slice(15, 17);
      campo.value = v;
    }

    function mascaratelefone(campo) {
      let v = campo.value.replace(/\D/g, ''); // Remove tudo que não for número
      if (v.length > 2) v = '(' + v.slice(0, 2) + ') ' + v.slice(2);
      if (v.length > 7) v = v.slice(0, 10) + '-' + v.slice(10, 14);
      campo.value = v;
    }
</script>
</body>
</html>
