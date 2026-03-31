<?php
session_start();
require 'db.php'; // conexão PDO

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $senha = $_POST['senha'];

    // verificar se já existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = :u");
    $stmt->execute([':u'=>$username]);
    if ($stmt->fetch()) {
        $error = "Usuário já existe!";
    } else {
        // gerar hash seguro
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, senha_hash) VALUES (:u, :s)");
        $stmt->execute([':u'=>$username, ':s'=>$hash]);
        $success = "Usuário criado com sucesso!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
<head><meta charset="UTF-8"><title>Criar Usuário</title></head>
<body>
<h1>Criar Login</h1>
<form method="post">
    
    Usuário: <input type="text" name="username" ><br>
    Senha: <input type="password" name="senha" ><br>
    <button type="submit">Cadastrar</button>
    <!-- ################################ -->
    <a  id="PgLog" href="login.php">Ir para Login</a>
    <!-- ################################ -->
</form>
<?php if (!empty($error)) echo "<p id='ero' style='color:red' >$error</p>"; ?>
<?php if (!empty($success)) echo "<p id='ero' style='color:green'>$success</p>"; ?>
<!-- <p><a href="login.php">Ir para Login</a></p> -->
</body>
</html>