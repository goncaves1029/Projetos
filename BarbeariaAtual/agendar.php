<?php


file_put_contents("log.txt", file_get_contents("php://input"));

$conn = new mysqli('localhost','bora','vamoslá','barbearia');

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["erro" => "Erro na conexão com o banco"]);
  exit;
}

// Lê o corpo da requisição JSON
$input = json_decode(file_get_contents("php://input"), true);

$nome = $input['nome'] ?? null;
$servico = $input['servico'] ?? null;
$data = $input['data'] ?? null;
$hora = $input['hora'] ?? null;

if (!$nome || !$servico || !$data || !$hora) {
  http_response_code(400);
  echo json_encode(["erro" => "Campos obrigatórios não preenchidos"]);
  exit;
}

// Verifica se o horário já está ocupado
$verifica = $conn->prepare("SELECT * FROM agendamento WHERE data = ? AND hora = ?");
$verifica->bind_param("ss", $data, $hora);
$verifica->execute();
$resultado = $verifica->get_result();

if ($resultado->num_rows > 0) {
  http_response_code(409);
  echo json_encode(["erro" => "Horário já agendado"]);
  exit;
}

// Insere no banco
$stmt = $conn->prepare("INSERT INTO agendamento (nome, servico, data, hora) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $servico, $data, $hora);

if ($stmt->execute()) {
  echo json_encode(["sucesso" => true]);
} else {
  http_response_code(500);
  echo json_encode(["erro" => "Erro ao agendar"]);
}

$stmt->close();
$verifica->close();
$conn->close();
?>
