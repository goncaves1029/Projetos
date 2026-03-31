<?php
// manage.php
require 'db.php'; // espera $pdo (PDO) definido em db.php

// Mensagem opcional
$msg = $_GET['msg'] ?? '';

// Buscar registros (ordenar por data/hora desc)
$sql = "SELECT id, culto, data, hora, adultos, criancas_templo, kids_baby, kids, link, louvor, comunicacao, voluntarios, pastor,pregador, carros, motos, bicicletas, created_at
        FROM presencas
        ORDER BY data DESC, hora DESC, id DESC";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
<title>Gerenciar Presenças</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
body{font-family:Arial, sans-serif;margin:20px}
table{border-collapse:collapse;width:100%;max-width:1200px}
th,td{border:1px solid #ddd;padding:8px;text-align:left}
th{background:#f4f4f4}
.actions form{display:inline}
.msg{padding:10px;background:#e7f7e7;border:1px solid #bfe6bf;margin-bottom:10px}
.small{font-size:0.9em;color:#666}
</style>
</head>
<body>

<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@teste@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
 

    <head>

    <div class="fab">
  <button class="main">
  </button>
  <ul>
    <li>
      <label for="opcao1">Opção 1</label>
       <a href="login.php"></a>
      <button id="opcao1" onclick="window.location.href='edit.php'">
      ⎈
      </button>
    </li>
    <li>
      <label for="opcao2">Opção 2</label>
      <button id="opcao2" onclick="window.location.href='delet.php'">
      ⎗
      </button>
    </li>
    <!-- <li>
      <label for="opcao3">Opção 3</label>
      <button id="opcao3" onclick="window.location.href='login.php'">
      ☏    
    </li> -->

  </ul>
</div>

 <script>function toggleFAB(fab){
	if(document.querySelector(fab).classList.contains('show')){
  	document.querySelector(fab).classList.remove('show');
  }else{
  	document.querySelector(fab).classList.add('show');
  }
}

document.querySelector('.fab .main').addEventListener('click', function(){
	toggleFAB('.fab');
});

document.querySelectorAll('.fab ul li button').forEach((item)=>{
	item.addEventListener('click', function(){
		toggleFAB('.fab');
	});
});</script>

 </head>

<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@teste@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ --> 

<h1>Gerenciar Presenças</h1>
<?php if ($msg): ?><div class="msg"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<p><a href="index.php">Voltar ao formulário</a></p>

<table>
<thead>
<tr>
<th>ID</th><th>Culto</th><th>Data</th><th>Hora</th><th>Adultos</th><th>Crianças</th><th>Kids Baby</th><th>Kids</th>
<th>Voluntários (link/louvor/comunicação/gerais/pastor/pregador)</th><th>Estac. (carros/motos/bicicletas)</th><th>Criado em</th><th>Ações</th>
</tr>
</thead>
<tbody>
<?php if ($rows): ?>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= htmlspecialchars($r['culto']) ?></td>
            <td><?= htmlspecialchars($r['data']) ?></td>
            <td><?= htmlspecialchars($r['hora']) ?></td>
            <td><?= (int)$r['adultos'] ?></td>
            <td><?= (int)$r['criancas_templo'] ?></td>
            <td><?= (int)$r['kids_baby'] ?></td>
            <td><?= (int)$r['kids'] ?></td>
            <td>
                <?= (int)$r['link'] ?> /
                <?= (int)$r['louvor'] ?> /
                <?= (int)$r['comunicacao'] ?> /
                <?= (int)$r['voluntarios'] ?> /
                <?= (int)$r['pastor'] ?> /

                <?= htmlspecialchars($r['pregador']) ?>
            </td>
            <td>
                <?= (int)$r['carros'] ?> /
                <?= (int)$r['motos'] ?> /
                <?= (int)$r['bicicletas'] ?>
            </td>
            <td class="small"><?= htmlspecialchars($r['created_at']) ?></td>
            <!-- dentro do loop que exibe cada linha -->
<td class="actions">
    <a id='PgLo' href="edit.php?id=<?= (int)$r['id'] ?>">Editar</a>

    <a id='PgLo' href="delete.php?id=<?= (int)$r['id'] ?>" onclick="return confirm('Deseja excluir o registro #<?= (int)$r['id'] ?>?');">Excluir</a>
</td>
            <!-- <td class="actions">
                <form method="get" action="edit.php" style="display:inline">
                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <button type="submit">Editar</button>
                </form>
                <form method="get" action="delete.php" style="display:inline" onsubmit="return confirm('Deseja prosseguir para excluir (será solicitada senha)?');">
                    <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                    <button type="submit">Excluir</button>
                </form>
            </td> -->
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="12">Nenhum registro encontrado.</td></tr>
<?php endif; ?>
</tbody>
</table>
</body>
</html>