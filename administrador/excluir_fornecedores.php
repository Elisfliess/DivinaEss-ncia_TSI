
<?php
session_start();
require_once('conexao_azure.php');

if (!isset($_SESSION['admin_logado'])) {
    header("Location:login.php");
    exit();
}

$id = $_GET['id'] ?? null; 
if (!$id) {
    header('Location: listar_fornecedores.php');
    exit();
}

try {
    $stmt = $pdo->prepare("DELETE FROM fornecedor WHERE id_fornecedor = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: listar_fornecedores.php');
    exit();
} catch (PDOException $e) {
    echo "<p style='color:red;'>Erro ao excluir fornecedor: " . $e->getMessage() . "</p>";
}
?>


