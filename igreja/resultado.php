<!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% TESTE %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->
 <?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>


<!-- %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% TESTE %%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%% -->

<?php
// resultado.php - recebe POST, insere no banco e exibe resumo
require 'db.php'; // garante que $pdo exista

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// --- Ler e sanitizar entradas ---
$culto = trim($_POST['culto'] ?? '');
$culto_extra = trim($_POST['culto_extra'] ?? '');
if ($culto === 'outro' && $culto_extra !== '') {
    $culto = $culto_extra;
}
$data = $_POST['data'] ?? null;
$hora = $_POST['hora'] ?? null;

// Campos numéricos (forçar inteiro)
$adultos = intval($_POST['adultos'] ?? 0);
$criancas_templo = intval($_POST['criancas_templo'] ?? 0);
$kids_baby = intval($_POST['kids_baby'] ?? 0);
$kids = intval($_POST['kids'] ?? 0);

$link = intval($_POST['link'] ?? 0);
$louvor = intval($_POST['louvor'] ?? 0);
$comunicacao = intval($_POST['comunicacao'] ?? 0);
$voluntarios = intval($_POST['voluntarios'] ?? 0);
$pastor = intval($_POST['pastor'] ?? 0);

// Pastores é texto (nomes ou contagem)
$pregador = trim($_POST['pregador'] ?? '');

// Estacionamento
$carros = intval($_POST['carros'] ?? 0);
$motos = intval($_POST['motos'] ?? 0);
$bicicletas = intval($_POST['bicicletas'] ?? 0);

// --- Inserir no banco com prepared statement PDO ---
try {
    $sql = "INSERT INTO presencas
        (culto, data, hora, adultos, criancas_templo, kids_baby, kids, link, louvor, comunicacao, voluntarios, pastor, pregador, carros, motos, bicicletas)
        VALUES
        (:culto, :data, :hora, :adultos, :criancas_templo, :kids_baby, :kids, :link, :louvor, :comunicacao, :voluntarios,:pastor, :pregador, :carros, :motos, :bicicletas)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':culto' => $culto,
        ':data' => $data,
        ':hora' => $hora,
        ':adultos' => $adultos,
        ':criancas_templo' => $criancas_templo,
        ':kids_baby' => $kids_baby,
        ':kids' => $kids,
        ':link' => $link,
        ':louvor' => $louvor,
        ':comunicacao' => $comunicacao,
        ':voluntarios' => $voluntarios,
        ':pastor' => $pastor,
        ':pregador' => $pregador,
        ':carros' => $carros,
        ':motos' => $motos,
        ':bicicletas' => $bicicletas
    ]);
    $inserted_id = $pdo->lastInsertId();
} catch (Exception $e) {
    error_log("Erro ao inserir presenca: " . $e->getMessage());
    die("Ocorreu um erro ao salvar o registro. Verifique a conexão com o banco.");
}

// --- Calcular totais para exibição ---
$total_pessoas = $adultos + $criancas_templo + $kids_baby + $kids;
$total_voluntarios = $link + $louvor + $comunicacao + $voluntarios + $pastor + (is_numeric($pregador) ? intval($pregador) : 0);
$total_geral = $total_pessoas + $total_voluntarios;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resumo da Presença</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body>
 
<!-- @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@teste@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ -->
 
    <head>

    <div class="fab">
  <button class="main">
  </button>
  <ul>
    <li>
      <label for="opcao1">Novo Registro</label>
      <button id="opcao1"  onclick="window.location.href='registro.php'" >
      
      </button>
    </li>
    <!-- <li>
      <label for="opcao2">Editar</label>
      <button id="opcao2" onclick="window.location.href='edit.php'">
      ⎗
      </button>
    </li>
    <li>
      <label for="opcao3">Deletar</label>
      <button id="opcao3" onclick="window.location.href='delete.php'">
      ☏
      </button>
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

  <div id="result">
     <h1>Lagoinha Macaíba</h1>
     <h2>Culto de <?= htmlspecialchars($culto) ?> - <?= htmlspecialchars($data) ?> às <?= htmlspecialchars($hora) ?></h2>

      <h3>Pessoas</h3>
      <p>Adultos: <?= $adultos ?></p>
      <p>Crianças/Templo: <?= $criancas_templo ?></p>
      <p>Kids Baby: <?= $kids_baby ?></p>
      <p>Kids: <?= $kids ?></p>
      <p><strong>Total Pessoas: <?= $total_pessoas ?></strong></p>

      <h3>Voluntários</h3>
      <p>Link: <?= $link ?></p>
      <p>Louvor: <?= $louvor ?></p>
      <p>Comunicação: <?= $comunicacao ?></p>
      <p>Voluntários Gerais: <?= $voluntarios ?></p>
      <p>pastor: <?= $pastor ?></p>
      <p>pregador: <?= htmlspecialchars($pregador) ?></p>
      <p><strong>Total Voluntários: <?= $total_voluntarios ?></strong></p>

      <h3>Estacionamento</h3>
      <p>Carros: <?= $carros ?></p>
      <p>Motos: <?= $motos ?></p>
      <p>Bicicletas: <?= $bicicletas ?></p>

      <h2>Total Geral: <?= $total_geral ?></h2>

      <!-- <p><a href="index.php">Registrar outro</a> | <a href="manage.php">Gerenciar registros</a></p> -->
      <p>ID do registro salvo: <?= (int)$inserted_id ?></p>
            
  
    </div>
    </canvas>
    <?php
    // montar mensagem (exemplo)
$mensagem = "Culto: $culto\n Data:$data\n às $hora\n".
            "Adultos: $adultos\nCrianças: $criancas_templo\n Kids Baby: $kids_baby\n Kids: $kids\n".
            "Voluntários: Link $link\n Louvor $louvor\n Comunicação $comunicacao\n Gerais $voluntarios\n Pastor $pastor\n Pregador $pregador\n\n".
            "Carros: $carros Motos: $motos Bicicletas: $bicicletas\n".
            "Total Pessoas: $total_pessoas\n Total Voluntários: $total_voluntarios\n Total Geral: $total_geral";


// $numero = '5584992134754'; // formato: 55 + DDD + número (sem +, sem espaços)
// $link_whatsapp = "https://wa.me/{$numero}?text=" . urlencode($mensagem);
$link_whatsapp = "https://wa.me/?text=" . urlencode($mensagem);

    ?>
 

      <button id='whats' type="button" onclick="window.open('<?= addslashes($link_whatsapp) ?>','_blank')"
        >
  📲 Enviar para WhatsApp
  
</button>





<!-- link simples -->
<!-- <p>
  <a href="" target="_blank"
     style="display:inline-block;padding:10px 16px;background:#25D366;color:#fff;text-decoration:none;border-radius:6px;">
     📲 Enviar para WhatsApp
  </a>
</p> -->

</body>
</html>