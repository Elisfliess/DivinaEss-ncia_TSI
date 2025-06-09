<?php
// Conexão com Azure SQL Server via PDO
$serverName = "tcp:projetointegradoro.database.windows.net";
$database = "Divina_Essência";
$username = "isa.avelina"; // login do SQL Server
$password = "1S@b3ll@";

try {
    $pdo = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // conexão bem-sucedida
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
?>
