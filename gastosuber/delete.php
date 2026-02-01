<?php
require_once __DIR__ . '/db.php';

if (isset($_GET['all']) && $_GET['all'] == '1') {
    $conn->query("DELETE FROM registros");
    echo "Todos os registros foram excluídos.";
    exit;
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $conn->query("DELETE FROM registros WHERE id = $id");
    echo "Registro excluído com sucesso.";
    exit;
}

echo "Nenhuma ação realizada.";
?>

