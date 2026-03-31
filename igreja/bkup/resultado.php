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
      <label for="opcao1">Opção 1</label>
      <button id="opcao1"  href="registro.php">
      ⎈
      </button>
    </li>
    <li>
      <label for="opcao2">Opção 2</label>
      <button id="opcao2">
      ⎗
      </button>
    </li>
    <li>
      <label for="opcao3">Opção 3</label>
      <button id="opcao3">
      ☏
      </button>
    </li>
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
    <h2>Gráfico</h2>
    <canvas id="grafico" class="grafico">
   
   <script>
const ctx = document.getElementById('grafico').getContext('2d');
const grafico = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: [
            'Adultos', 'Crianças/Templo', 'Kids Baby', 'Kids',
            'Link', 'Louvor', 'Comunicação', 'Voluntários Gerais', 'Pastor', 'Pregador'
        ],
        datasets: [{
            data: [
                <?= $adultos ?>, <?= $criancas_templo ?>, <?= $kids_baby ?>, <?= $kids ?>,
                <?= $link ?>, <?= $louvor ?>, <?= $comunicacao ?>, <?= $voluntarios ?>,
                <?= $pastor ?>, <?= (is_numeric($pregador) ? intval($pregador) : 0) ?>
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)',
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(75, 192, 192, 0.6)',
                'rgba(153, 102, 255, 0.6)',
                'rgba(255, 159, 64, 0.6)',
                'rgba(199, 199, 199, 0.6)',
                'rgba(0, 128, 128, 0.6)',
                'rgba(128, 0, 128, 0.6)',
                'rgba(26, 255, 0, 0.6)'
            ]
        }]
    },
    options: {
        plugins: {
            datalabels: {
                formatter: (value, ctx) => {
                    let dataset = ctx.chart.data.datasets[0].data;
                    let total = dataset.reduce((a, b) => a + b, 0);
                    if (total === 0) return '0%';
                    return (value / total * 100).toFixed(1) + "%";
                },
                color: '#fff',
                font: { weight: 'bold' }
            }
        }
    },
    plugins: [ChartDataLabels]
});
</script>
</canvas>
</body>
</html>