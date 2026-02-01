<?php
require_once __DIR__ . '/db.php';

$result = $conn->query("SELECT * FROM registros ORDER BY id DESC");

$registros = [];

while ($row = $result->fetch_assoc()) {
    $registros[] = $row;
}

header('Content-Type: application/json');
echo json_encode($registros);
?>