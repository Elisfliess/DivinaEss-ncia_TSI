<?php
session_start();

require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $ativo = isset($_POST['ativo']) ? 1 : 0; 
    try {
        $sql = "INSERT INTO ADMINISTRADOR (ADM_NOME, ADM_EMAIL, ADM_SENHA,ADM_ATIVO) VALUES (:nome, :email, :senha, :ativo);";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
        $stmt->bindParam(':ativo', $ativo, PDO::PARAM_INT); 

        $stmt->execute(); 
        $adm_id = $pdo->lastInsertId();

        
        echo "<p style='color:green;'>Administrador cadastrado com sucesso! ID: " . $adm_id . "</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erro ao cadastrar Administrador: " . $e->getMessage() . "</p>";
    }
}
?>

<!-- Início do código HTML -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar - Divina Essência</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
        <!--Começa Cabeçalho-->
    <div class="containe-cabecalhoemenu">
      <header class="Cabecalho">
        <div class="container-cabecalho">
          <div class="logo">
              <a href="../E-commerce/index.html">
                  <img src="../img/Logo.png">
              </a>
          </div>
          <div class="campodebusca1">
              <input type="text" placeholder="O que procura?">
              <button type="button" class="btn-busca">
                <i class="bi bi-search"></i>
              </button>
          </div>
          <div class="opcoes-usuario">
                  <img src="../img/login.png" alt="">
      
                  <div class="container-login">
              <h6>Minha Conta</h6>
                  <a href="../administrador/login.php">Entrar /</a><a href="../administrador/cadastrar_user_admin.php"> Cadastrar</a> 
          </div>
          <div class="carrinho">
              <img src="../img/carinho.png" alt="">
              <span class="quantidade-carrinho">0</span>          
          </div>
          </div>
        </div>
      
        </header>
          <!--Fecha cabecalho-->
      
          <!--Começa Menu-->
          <div class="menu">
            <nav class="container-menu">
                <ul>
                    <li><a href="sobrenos.html">Sobre Nós</a></li>
                    <li><a href="sabonete.html">Sabonetes</a></li>
                    <li><a href="acessorios.html">Acessórios</a></li>
                    <li><a href="vela.html">Vela</a></li>
                    <li><a href="aromatizante.html">Aromatizador</a></li>
                    <li><a href="oleoessencial.html">Oléo essencial</a></li>
                </ul>
            </nav>
        </div>
      </div>
          <!--Fecha menu-->
  
    <!--Começa campo de cadastro-->
    <div class="cadastrar">
        <h1>Cadastro de novo cliente</h1>
        <form class="container-form">
            <div class="form-control2" >
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nomeo" placeholder="Digite nome completo">
            <br> 
                <label for="email">Email:</label>
                <input type="email" id="email" placeholder="Digite seu email">
            <br> 
                <label for="senha">Crie uma senha de acesso:</label>
                <input type="password" id="senha" placeholder="Digite uma senha de 8 digitos">
            <br>
                <label for="ativo">Ativo:</label>
                <input type="checkbox" name="ativo" id="ativo" value="1" checked>
            </div>
            <p>Já possue <a href="entrar.html">login</a>?</p>
            <button type="submit">Cadastrar</button>
        </form>
    </div>
      <!--Fecha campo de cadastro-->

    <!--Começa rodapé-->
    <div class="contato">
        <div class="container-tudo">
        <div class="containe-email">
          <div class="email">
            <div class="container-iconeeescrita">
            <i class="bi bi-envelope"></i>          
            <p>Receba nossas Novidades</p>
          </div>
            <div class="campodebusca1">
              <input type="text" placeholder="Digite seu email">
          </div>
          <button>Enviar</button>
          </div>
        </div>
      <div class="final-redessociais">
          <h4>Redes Sociais</h4>
          <div class="container-redes">
          <div class="redes">
          <i class="bi bi-instagram"></i>        
          <a href="#">@divina_essencia</a>   
          </div>
          <div class="redes">
          <i class="bi bi-facebook"></i> 
          <a href="#">Divina Essência</a>
          <link rel="stylesheet" href="#">
        </div>
      </div>
      </div>
      </div>
    </div>
        <footer>
          <p>Todos Direitos Reservados a Isadora Burgos e Isabella Avelina - 2024@</p>
        </footer>
    </div>
      <!--Fecha rodapé-->
  <!--Começa icone fixo de wpp-->
    <div class="whats">
        <a href="https://wa.me/5511913119603" target="_blank">
        <img src="../img/wppsemfundo.png"  width="70" alt="whatsapp" title="Fale conosco pelo whatsapp">
      </a>
        <!--Fecha icone fixo de wpp-->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
  </html>