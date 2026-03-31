<?php
require 'db.php'; // conexão PDO

$username = 'admin';
$senha = '123456';
$hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO usuarios (username, senha_hash) VALUES (:u, :s)");
$stmt->execute([':u'=>$username, ':s'=>$hash]);

echo "Usuário criado!";
?>


