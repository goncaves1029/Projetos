<?php


// debug delete.php - cole por cima do seu arquivo para depurar
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!file_exists(__DIR__ . '/db.php')) {
    die("Arquivo db.php não encontrado em " . __DIR__ . ". Coloque db.php na mesma pasta.");
}

require __DIR__ . '/db.php'; // espera $pdo (PDO) definido em db.php

define('DELETE_PASS', 'vitaminac'); // ajuste se necessário

// obter id de GET ou POST
$id = 0;
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
}

if ($id <= 0) {
    die("ID inválido recebido: " . htmlspecialchars((string)$id) . ". Verifique a URL: " . htmlspecialchars($_SERVER['REQUEST_URI']));
}

// buscar registro
try {
    $stmt = $pdo->prepare("SELECT id, culto, data, hora FROM presencas WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();
} catch (Exception $e) {
    die("Erro ao consultar o banco: " . $e->getMessage());
}

if (!$row) {
    die("Registro não encontrado para id = $id");
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['delete_password'] ?? '';
    if ($password !== DELETE_PASS) {
        $error = "Senha de exclusão incorreta.";
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM presencas WHERE id = :id");
            $stmt->execute([':id' => $id]);
            header('Location: manage.php?msg=' . urlencode('Registro excluído com sucesso.'));
            exit;
        } catch (Exception $e) {
            die("Erro ao excluir: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
<title>Excluir Presença (debug)</title>
</head>
<body>
    <h1>Excluir registro #<?= (int)$id ?></h1>
    <p><a href="manage.php">Voltar</a></p>

    <?php if ($error): ?><div style="color:#a00"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <p>Registro: <?= htmlspecialchars($row['culto']) ?> — <?= htmlspecialchars($row['data']) ?> <?= htmlspecialchars($row['hora']) ?></p>

    <form method="post" action="delete.php?id=<?= (int)$id ?>">
        <input type="hidden" name="id" value="<?= (int)$id ?>">
        <label>Senha de exclusão:
            <input type="password" name="delete_password" required>
        </label>
        <button type="submit">Confirmar exclusão</button>
    </form>
</body>
</html>