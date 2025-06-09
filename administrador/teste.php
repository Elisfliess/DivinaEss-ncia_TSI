<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    require_once('conexao_azure.php');

    $email = 'teste@divina.com'; 
    $sql = "SELECT * FROM administrador WHERE adm_email = :email";
    $query = $pdo->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);

    if ($query->execute()) {
        echo "<p>Consulta executada com sucesso.</p>";

        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultados) > 0) {
            echo "<pre>";
            print_r($resultados);
            echo "</pre>";
        } else {
            echo "<p>Nenhum usuário encontrado com o email informado.</p>";
        }
    } else {
        echo "<p>Erro ao executar a consulta:</p>";
        print_r($query->errorInfo());
    }

} catch (PDOException $e) {
    echo "<p>Erro de conexão: " . $e->getMessage() . "</p>";
}
?>