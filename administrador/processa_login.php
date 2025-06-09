<?php
session_start(); 

try {
    require_once('conexao_azure.php');

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM administrador WHERE adm_email = :email AND adm_senha = :senha AND adm_ativo = 1"; 
    $query = $pdo->prepare($sql);

    
    $query->bindParam(':email', $email, PDO::PARAM_STR); 
    $query->bindParam(':senha', $senha, PDO::PARAM_STR); 
    $query->execute();

   
    if ($query->execute()) {
        
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);


        if ($query->rowCount() > 0) {
        $_SESSION['admin_logado'] = true;
        header('Location: painel_admin.php');
        exit;
    } else {
        $_SESSION['mensagem_erro'] = "Nome de usuário ou senha incorreto.";
        header('Location: login.php');
        exit;
    }
    
    } else {
        $_SESSION['mensagem_erro'] = "NOME DE USUÁRIO OU SENHA INCORRETO";
        header('Location: login.php?erro');
        exit; 
    }
} catch (Exception $e) {
    $_SESSION['mensagem_erro'] = "Erro de conexão: " . $e->getMessage();
    header('Location: login.php?erro');
    exit; 
}

?>
