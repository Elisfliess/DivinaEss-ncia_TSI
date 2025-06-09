<?php
session_start();
require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

$id = $_GET['id_produto'] ?? null;

if (!$id) {
    header('Location: listar_produtos.php');
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM produto WHERE id_produto = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: listar_produtos.php');
    exit();
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao excluir produto: " . $e->getMessage() . "</p>";
}
?>
