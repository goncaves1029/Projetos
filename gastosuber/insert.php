<?php
require_once __DIR__ . '/db.php'; // garante caminho correto

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($conn)) {
    http_response_code(400);
    echo "Erro: dados inválidos ou conexão ausente.";
    exit;
}

$data            = $conn->real_escape_string($input['data']);
$km_inicial      = (int) $input['km_inicial'];
$km_final        = (int) $input['km_final'];
$km_rodados      = (int) $input['km_rodados'];
$litros          = (float) $input['litros'];
$valor_total     = (float) $input['valor_total'];
$consumo_estimado= (float) $input['consumo_estimado'];
$preco_por_litro = (float) $input['preco_por_litro'];
$gasto_combustivel = (float) $input['gasto_combustivel'];
$diaria          = (float) $input['diaria'];
$lucro_liquido   = (float) $input['lucro_liquido'];

$sql = "INSERT INTO registros (
    data, km_inicial, km_final, km_rodados, litros, valor_total,
    consumo_estimado, preco_por_litro, gasto_combustivel, diaria, lucro_liquido
) VALUES (
    '$data', $km_inicial, $km_final, $km_rodados, $litros, $valor_total,
    $consumo_estimado, $preco_por_litro, $gasto_combustivel, $diaria, $lucro_liquido
)";

if ($conn->query($sql)) {
    echo "Registro inserido com sucesso!";
} else {
    http_response_code(500);
    echo "Erro ao inserir: " . $conn->error;
}
?>