<?php
$conn = new mysqli('localhost','bora','vamoslá','barbearia');

$data = $_GET['data'] ?? null;

$todosHorarios = [
  "09:00", "09:30", "10:00", "10:30",
  "11:00", "11:30", "12:00", "12:30", "14:00", "14:30",
  "15:00", "15:30", "16:00", "16:30", "17:00", "17:30",
];

if ($data) {
  $stmt = $conn->prepare("SELECT hora FROM agendamento WHERE data = ?");
  $stmt->bind_param("s", $data);
  $stmt->execute();
  $result = $stmt->get_result();

  $ocupados = [];
  while ($row = $result->fetch_assoc()) {
    $horaFormatada = date("H:i", strtotime(trim($row['hora'])));
    $ocupados[] = $horaFormatada;
  }

  $disponiveis = array_diff($todosHorarios, $ocupados);
  echo json_encode(array_values($disponiveis));
}

$conn->close();
?>