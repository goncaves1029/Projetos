<?php
require_once __DIR__ . '/db.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "ID não informado"]);
    exit;
}

$id = (int) $_GET['id'];
$result = $conn->query("SELECT * FROM registros WHERE id = $id");

if ($row = $result->fetch_assoc()) {
    header('Content-Type: application/json');
    echo json_encode($row);
} else {
    http_response_code(404);
    echo json_encode(["error" => "Registro não encontrado"]);
}
?>