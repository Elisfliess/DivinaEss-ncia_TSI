<?php
session_start();
require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: listar_categorias.php');
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM subcategoria WHERE id_categoria = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $pdo->prepare("DELETE FROM categoria WHERE id_categoria = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: listar_categorias.php');
    exit();
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao excluir categoria: " . $e->getMessage() . "</p>";
}

?>
