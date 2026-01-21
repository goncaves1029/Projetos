<?php
session_start();
$conn = new mysqli('localhost','bora','vamoslá','barbearia');
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $usuario = $_POST['usuario'];
  $senha = $_POST['senha'];

  $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE usuario = ?");
  $stmt->bind_param("s", $usuario);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($senha_hash);
    $stmt->fetch();

    if (password_verify($senha, $senha_hash)) {
      $_SESSION['usuario'] = $usuario;
      header("Location: cancelamento.php");
      exit;
    } else {
      $erro = "Senha incorreta.";
    }
  } else {
    $erro = "Usuário não encontrado.";
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    body {
      background-color: #302f2f;
      color: white;
      font-family: Arial, sans-serif;
      text-align: center;
      padding-top: 100px;
    }
    input {
      padding: 10px;
      margin: 10px;
      width: 200px;
    }
    button {
      padding: 10px 20px;
      background-color: #f7cb07;
      border: none;
      font-weight: bold;
    }
    .erro {
      color: red;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <h2>Login</h2>
  <form method="POST">
    <input type="text" name="usuario" placeholder="Usuário" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <button type="submit">Entrar</button>
  </form>
  <?php if (isset($erro)) echo "<div class='erro'>$erro</div>"; ?>
</body>
</html>