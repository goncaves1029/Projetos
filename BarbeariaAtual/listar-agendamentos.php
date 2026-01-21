<?php
$conn = new mysqli('localhost','bora','vamoslá','barbearia');
if ($conn->connect_error) {
  die("Erro na conexão com o banco.");
}

$result = $conn->query("SELECT idAgendamento, nome, servico, data, hora FROM agendamento ORDER BY data ASC, hora ASC");

while ($row = $result->fetch_assoc()) {
  echo "<tr>";
  echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
  echo "<td>" . htmlspecialchars($row['servico']) . "</td>";
  echo "<td>" . date("d/m/Y", strtotime($row['data'])) . "</td>";
  echo "<td>" . $row['hora'] . "</td>";
  echo "<td><a class='cancelar' href='cancelamento.php?cancelar=" . $row['idAgendamento'] . "' onclick='return confirm(\"Cancelar este agendamento?\")'>Cancelar</a></td>";
  echo "</tr>";
}
?>
