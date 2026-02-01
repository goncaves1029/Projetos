<?php
$host = "localhost";
$user = "root"; // ou seu usuário
$pass = "admin";     // ou sua senha
$db   = "listacasamento";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>