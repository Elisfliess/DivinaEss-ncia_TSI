<?php

session_start();

$mensagemErro = '';
if (isset($_SESSION['mensagem_erro'])) {
    $mensagemErro = $_SESSION['mensagem_erro'];
    unset($_SESSION['mensagem_erro']); // Limpa após exibir
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - Divina Essência</title>
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
              <img src="../img/Logo.png" alt="Logo da Divina Essência">
          </a>
      </div>
      <div class="campodebusca1">
          <input type="text" placeholder="O que procura?">
          <button type="button" class="btn-busca">
            <i class="bi bi-search"></i>
          </button>
      </div>
      <div class="opcoes-usuario">
          <img src="../img/login.png" alt="Ícone de login">
          <div class="container-login">
              <h6>Minha Conta</h6>
              <a href="../administrador/login.php">Entrar /</a><a href="../E-commerce/cadastrar.html"> Cadastrar</a> 
          </div>
          <div class="carrinho">
              <img src="../img/carinho.png" alt="Carrinho de compras">
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
            <li><a href="../E-commerce/sobrenos.html">Sobre Nós</a></li>
            <li><a href="../E-commerce/sabonete.html">Sabonetes</a></li>
            <li><a href="../E-commerce/sacessorios.html">Acessórios</a></li>
            <li><a href="../E-commerce/vela.html">Vela</a></li>
            <li><a href="../E-commerce/aromatizante.html">Aromatizador</a></li>
            <li><a href="../E-commerce/oleoessencial.html">Oléo essencial</a></li>
        </ul>
    </nav>
  </div>
</div>
<!--Fecha menu-->

<!--Começa campo de login-->
<!-- Começa campo de login -->
<div class="login">
    <h1>Login</h1>

    <!-- Mensagem de erro, se houver -->
    <?php if (!empty($mensagemErro)): ?>
        <div style="color: red; font-weight: bold; text-align: center; margin-bottom: 10px;">
            <?= htmlspecialchars($mensagemErro) ?>
        </div>
    <?php endif; ?>

    <form class="form-control" action="processa_login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Digite seu email" required>
        <br> <br>
        <!-- senha -->
       <label for="senha">Senha:</label>
       <div class="input-group">
    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
    <span class="eye" onclick="alternarSenha()">
        <i class="fa-solid fa-eye" id="iconeOlho"></i>
    </span>
</div>
        <br>
    <!-- senha -->

        <p>Não possui login? Se <a href="cadastrar.html">cadastre</a>!</p>
        <input type="submit" value="Entrar">
    </form>
</div>

<!--Fecha campo de login-->

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
        </div>
      </div>
    </div>
  </div>
</div>

<footer>
  <p>Todos Direitos Reservados a Isabella Avelina, Isadora Burgos, Lise Fliess e Pedro Almeida - 2025@</p>
</footer>
<!--Fecha rodapé-->

<!--Começa ícone fixo de WhatsApp-->
<div class="whats">
  <a href="https://wa.me/" target="_blank">
    <img src="../img/wppsemfundo.png" width="70" alt="whatsapp" title="Fale conosco pelo whatsapp">
  </a>
</div>
<!--Fecha ícone fixo de WhatsApp-->

<!--Toggle Password-->
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