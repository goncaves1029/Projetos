<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

// Conexão com o banco
$conn = new mysqli('localhost','bora','vamoslá','barbearia');

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(["erro" => "Erro na conexão com o banco"]);
  exit;
}

// Recebe a data via GET
$data = $_GET['data'] ?? null;

if (!$data) {
  http_response_code(400);
  echo json_encode(["erro" => "Data não fornecida"]);
  $conn->close();
  exit;
}

// Lista de feriados (formato: YYYY-MM-DD)
$feriados = ['2025-11-20', '2025-12-25'];

// Verifica se é feriado
if (in_array($data, $feriados)) {
  http_response_code(200);
  echo json_encode(["alerta" => "Não funcionaremos nesta data devido ao feriado."]);
  $conn->close();
  exit;
}

// Datas específicas liberadas para segunda-feira
$segundasLiberadas = ['2025-11-17', '2025-12-01'];

// Verifica o dia da semana
$diaSemana = date('w', strtotime($data)); // 0 = domingo, 1 = segunda, ..., 6 = sábado

// Define os horários disponíveis com base no dia
if ($diaSemana == 6) {
  // Sábado
  $todosHorarios = [
    "08:00", "08:30", "09:00", "09:30",
    "10:00", "10:30", "11:00", "11:30",
    "12:00", "12:30", "13:00", "13:30",
    "14:00", "14:30", "15:00", "15:30",
    "16:00", "16:30", "17:00", "17:30",
    "18:00", "18:30"
  ];
} elseif ($diaSemana >= 2 && $diaSemana <= 5) {
  // Terça a sexta
  $todosHorarios = [
    "09:00", "09:30", "10:00", "10:30",
    "11:00", "11:30", "12:00", "12:30",
    "14:00", "14:30", "15:00", "15:30",
    "16:00", "16:30", "17:00", "17:30"
  ];
} elseif ($diaSemana == 1 && in_array($data, $segundasLiberadas)) {
  // Segunda-feira liberada
  $todosHorarios = [
    "09:00", "09:30", "10:00", "10:30",
    "11:00", "11:30", "12:00", "12:30",
    "14:00", "14:30", "15:00", "15:30",
    "16:00", "16:30", "17:00", "17:30"
  ];
} else {
  // Domingo ou segunda não permitida
  echo json_encode([]);
  $conn->close();
  exit;
}

// Consulta os horários ocupados
$stmt = $conn->prepare("SELECT hora FROM agendamento WHERE data = ?");
$stmt->bind_param("s", $data);
$stmt->execute();
$result = $stmt->get_result();

$ocupados = [];
while ($row = $result->fetch_assoc()) {
  $horaFormatada = date("H:i", strtotime(trim($row['hora'])));
  $ocupados[] = $horaFormatada;
}

$stmt->close();
$conn->close();

// Calcula os horários disponíveis
$disponiveis = array_diff($todosHorarios, $ocupados);
echo json_encode(array_values($disponiveis));
?>