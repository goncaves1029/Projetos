<?php
require_once __DIR__ . '/db.php';

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($conn)) {
    http_response_code(400);
    echo "Erro: dados inválidos ou conexão ausente.";
    exit;
}

$id              = (int) $input['id'];
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

$sql = "UPDATE registros SET 
    data='$data',
    km_inicial=$km_inicial,
    km_final=$km_final,
    km_rodados=$km_rodados,
    litros=$litros,
    valor_total=$valor_total,
    consumo_estimado=$consumo_estimado,
    preco_por_litro=$preco_por_litro,
    gasto_combustivel=$gasto_combustivel,
    diaria=$diaria,
    lucro_liquido=$lucro_liquido
WHERE id=$id";

if ($conn->query($sql)) {
    echo "Registro atualizado com sucesso!";
} else {
    http_response_code(500);
    echo "Erro ao atualizar: " . $conn->error;
}
?>