<?php
require_once('conexao_azure.php');

$tipo = $_GET['tipo'] ?? '';
$filename = $tipo . ".csv";

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: inline; filename=$filename"); 


echo "\xEF\xBB\xBF"; 

switch ($tipo) {
    case 'administrador':
        echo "ID Administrador,Nome,Email,Senha,Ativo\n";
        $stmt = $pdo->prepare("SELECT * FROM administrador");
        break;

    case 'categoria':
        echo "ID Categoria,Nome\n";
        $stmt = $pdo->prepare("SELECT * FROM categoria");
        break;

    case 'subcategoria':
        echo "ID Subcategoria,ID Categoria,Nome\n";
        $stmt = $pdo->prepare("SELECT * FROM subcategoria");
        break;

    case 'fornecedor':
        echo "ID Fornecedor,Nome,Email,Cidade,CNPJ,Endereço,Telefone\n";
        $stmt = $pdo->prepare("SELECT * FROM fornecedor");
        break;

    case 'produto':
        echo "ID Produto,Nome,Imagem,Fornecedor,Descrição,Subcategoria,Estoque,Preço\n";
        $stmt = $pdo->prepare("SELECT * FROM produto");
        break;

    default:
        die("Tipo inválido para exportação.");
}

$stmt->execute();
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($dados as $linha) {
    $linha_formatada = array_map(function($v) {
        return '"' . str_replace('"', '""', $v) . '"';
    }, array_values($linha));

    echo implode(",", $linha_formatada) . "\n";
}
