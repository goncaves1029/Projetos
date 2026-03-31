<?php

// edit.php
require 'db.php'; // espera $pdo

// **Defina aqui a senha de edição**
define('EDIT_PASS', 'TonydoCone'); // ALTERE para sua senha

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
if ($id <= 0) {
    header('Location: manage.php?msg=' . urlencode('ID inválido.'));
    exit;
}

$error = '';
$show_form = false;
$row = null;

// Se o usuário submeteu o formulário de salvar edição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_edit') {
    $password = $_POST['edit_password'] ?? '';
    if ($password !== EDIT_PASS) {
        $error = "Senha de edição incorreta.";
    } else {
        // coletar e sanitizar campos
        $culto = trim($_POST['culto'] ?? '');
        if ($culto === 'outro' && !empty($_POST['culto_extra'])) $culto = trim($_POST['culto_extra']);
        $data = $_POST['data'] ?: null;
        $hora = $_POST['hora'] ?: null;

        $adultos = intval($_POST['adultos'] ?? 0);
        $criancas_templo = intval($_POST['criancas_templo'] ?? 0);
        $kids_baby = intval($_POST['kids_baby'] ?? 0);
        $kids = intval($_POST['kids'] ?? 0);
        $link = intval($_POST['link'] ?? 0);
        $louvor = intval($_POST['louvor'] ?? 0);
        $comunicacao = intval($_POST['comunicacao'] ?? 0);
        $voluntarios = intval($_POST['voluntarios'] ?? 0);
        $pastor = intval($_POST['pastor'] ?? 0);
        $pregador = trim($_POST['pregador'] ?? '');
        $carros = intval($_POST['carros'] ?? 0);
        $motos = intval($_POST['motos'] ?? 0);
        $bicicletas = intval($_POST['bicicletas'] ?? 0);

        // Atualizar registro (prepared statement)
        $sql = "UPDATE presencas SET culto=:culto, data=:data, hora=:hora,
                adultos=:adultos, criancas_templo=:criancas_templo, kids_baby=:kids_baby, kids=:kids,
                link=:link, louvor=:louvor, comunicacao=:comunicacao, voluntarios=:voluntarios, pastor=:pastor, pregador=:pregador,
                carros=:carros, motos=:motos, bicicletas=:bicicletas
                WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':culto'=>$culto, ':data'=>$data, ':hora'=>$hora,
            ':adultos'=>$adultos, ':criancas_templo'=>$criancas_templo, ':kids_baby'=>$kids_baby, ':kids'=>$kids,
            ':link'=>$link, ':louvor'=>$louvor, ':comunicacao'=>$comunicacao, ':voluntarios'=>$voluntarios, ':pastor'=>$pastor, ':pregador'=>$pregador,
            ':carros'=>$carros, ':motos'=>$motos, ':bicicletas'=>$bicicletas, ':id'=>$id
        ]);
        header('Location: manage.php?msg=' . urlencode('Registro atualizado com sucesso.'));
        exit;
    }
}

// Se veio via GET para iniciar edição: pedir senha primeiro (mostrar breve info)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['confirm'])) {
    $stmt = $pdo->prepare("SELECT id, culto, data, hora FROM presencas WHERE id = :id");
    $stmt->execute([':id'=>$id]);
    $row_brief = $stmt->fetch();
    if (!$row_brief) {
        header('Location: manage.php?msg=' . urlencode('Registro não encontrado.'));
        exit;
    }
    // mostrar prompt de senha (HTML abaixo)
}

// Se senha foi verificada para mostrar o formulário (POST action=check_password)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'check_password') {
    $password = $_POST['edit_password'] ?? '';
    if ($password !== EDIT_PASS) {
        $error = "Senha de edição incorreta.";
    } else {
        // buscar registro completo e exibir formulário
        $stmt = $pdo->prepare("SELECT * FROM presencas WHERE id = :id");
        $stmt->execute([':id'=>$id]);
        $row = $stmt->fetch();
        if (!$row) {
            header('Location: manage.php?msg=' . urlencode('Registro não encontrado.'));
            exit;
        }
        $show_form = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Editar Presença</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
<style>body{font-family:Arial, sans-serif;margin:20px} label{display:block;margin-top:8px}</style>
</head>
<body>
<h1>Editar registro #<?= (int)$id ?></h1>
<p><a href="manage.php">Voltar</a></p>
<?php if ($error): ?><div style="color:#a00"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<?php if (!$show_form): ?>
    <?php if (isset($row_brief)): ?>
        <p>Registro: <?= htmlspecialchars($row_brief['culto']) ?> — <?= htmlspecialchars($row_brief['data']) ?> <?= htmlspecialchars($row_brief['hora']) ?></p>
    <?php endif; ?>
    <form method="post" action="edit.php?id=<?= (int)$id ?>">
        <input type="hidden" name="action" value="check_password">
        <label>Senha de edição:
            <input type="password" name="edit_password" required>
        </label>
        <button type="submit">Entrar para editar</button>
    </form>
<?php else: ?>
    <form method="post" action="edit.php?id=<?= (int)$id ?>">
        <input type="hidden" name="action" value="save_edit">
        <label>Senha de edição:
            <input type="password" name="edit_password" required>
        </label>

        <label>Culto:
            <input type="text" name="culto" value="<?= htmlspecialchars($row['culto']) ?>">
        </label>
        <label>Data:
            <input type="date" name="data" value="<?= htmlspecialchars($row['data']) ?>">
        </label>
        <label>Hora:
            <input type="time" name="hora" value="<?= htmlspecialchars($row['hora']) ?>">
        </label>

        <h3>Pessoas</h3>
        <label>Adultos: <input type="number" name="adultos" value="<?= (int)$row['adultos'] ?>"></label>
        <label>Crianças/Templo: <input type="number" name="criancas_templo" value="<?= (int)$row['criancas_templo'] ?>"></label>
        <label>Kids Baby: <input type="number" name="kids_baby" value="<?= (int)$row['kids_baby'] ?>"></label>
        <label>Kids: <input type="number" name="kids" value="<?= (int)$row['kids'] ?>"></label>

        <h3>Voluntários</h3>
        <label>Link: <input type="number" name="link" value="<?= (int)$row['link'] ?>"></label>
        <label>Louvor: <input type="number" name="louvor" value="<?= (int)$row['louvor'] ?>"></label>
        <label>Comunicação: <input type="number" name="comunicacao" value="<?= (int)$row['comunicacao'] ?>"></label>
        <label>Voluntários Gerais: <input type="number" name="voluntarios" value="<?= (int)$row['voluntarios'] ?>"></label>
        <label>Pastor: <input type="number" name="pastor" value="<?= (int)$row['pastor'] ?>"></label>
        <label>Pregador (texto ou nomes separados por vírgula): <input type="text" name="pregador" value="<?= htmlspecialchars($row['pregador']) ?>"></label>

        <h3>Estacionamento</h3>
        <label>Carros: <input type="number" name="carros" value="<?= (int)$row['carros'] ?>"></label>
        <label>Motos: <input type="number" name="motos" value="<?= (int)$row['motos'] ?>"></label>
        <label>Bicicletas: <input type="number" name="bicicletas" value="<?= (int)$row['bicicletas'] ?>"></label>

        <p><button type="submit">Salvar alterações</button></p>
    </form>
<?php endif; ?>
</body>
</html>