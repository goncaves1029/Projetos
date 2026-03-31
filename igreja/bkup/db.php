<?php
// db.php - conexão PDO (incluir em outros arquivos com require 'db.php')
$dbHost = 'localhost';
$dbName = 'igreja';
$dbUser = 'root';      // altere para o usuário que você criou
$dbPass = 'admin';   // altere para a senha que você criou
$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
    error_log("Erro PDO: " . $e->getMessage());
    die("Erro ao conectar ao banco de dados. Verifique as credenciais em db.php.");
}