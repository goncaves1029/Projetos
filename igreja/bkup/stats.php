<?php
// stats.php
// Requer: db.php (PDO) na mesma pasta que define $pdo
require 'db.php';

// Expressão para contar pastores quando o campo contém nomes separados por vírgula
$pastores_count_expr = "SUM(
    CASE
      WHEN TRIM(pastores) = '' OR pastores IS NULL THEN 0
      ELSE LENGTH(pastores) - LENGTH(REPLACE(pastores, ',', '')) + 1
    END
) AS pastores_count";

// parâmetros padrão
$period = $_GET['period'] ?? 'semana'; // dia, semana, mes, trimestre, ano
$ref_date = $_GET['date'] ?? date('Y-m-d'); // data de referência para filtros
$year_input = $_GET['year'] ?? date('Y'); // para filtro por ano ou mês/ano
$month_input = $_GET['month'] ?? date('m'); // para filtro por mês (MM)
$error = null;
$data_row = null;

try {
    switch ($period) {
        case 'dia':
            // filtrar por data exata
            $sql = "SELECT
                        SUM(adultos) AS adultos,
                        SUM(criancas_templo) AS criancas_templo,
                        SUM(kids_baby) AS kids_baby,
                        SUM(kids) AS kids,
                        SUM(link) AS link,
                        SUM(louvor) AS louvor,
                        SUM(comunicacao) AS comunicacao,
                        SUM(voluntarios) AS voluntarios,
                        $pastores_count_expr
                    FROM presencas
                    WHERE data = :date";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':date' => $ref_date]);
            break;

        case 'semana':
            // usa YEARWEEK para a semana da data de referência
            $sql = "SELECT
                        SUM(adultos) AS adultos,
                        SUM(criancas_templo) AS criancas_templo,
                        SUM(kids_baby) AS kids_baby,
                        SUM(kids) AS kids,
                        SUM(link) AS link,
                        SUM(louvor) AS louvor,
                        SUM(comunicacao) AS comunicacao,
                        SUM(voluntarios) AS voluntarios,
                        $pastores_count_expr
                    FROM presencas
                    WHERE YEARWEEK(data, 1) = YEARWEEK(:date, 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':date' => $ref_date]);
            break;

        case 'mes':
            // filtrar por mês e ano
            $sql = "SELECT
                        SUM(adultos) AS adultos,
                        SUM(criancas_templo) AS criancas_templo,
                        SUM(kids_baby) AS kids_baby,
                        SUM(kids) AS kids,
                        SUM(link) AS link,
                        SUM(louvor) AS louvor,
                        SUM(comunicacao) AS comunicacao,
                        SUM(voluntarios) AS voluntarios,
                        $pastores_count_expr
                    FROM presencas
                    WHERE MONTH(data) = :month AND YEAR(data) = :year";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':month' => $month_input, ':year' => $year_input]);
            break;

        case 'trimestre':
            // trimestre = ceil(month/3)
            // calculamos o trimestre da data de referência
            $month_of_ref = (int)date('n', strtotime($ref_date));
            $quarter = (int)ceil($month_of_ref / 3);
            // filtrar por ano e trimestre
            $sql = "SELECT
                        SUM(adultos) AS adultos,
                        SUM(criancas_templo) AS criancas_templo,
                        SUM(kids_baby) AS kids_baby,
                        SUM(kids) AS kids,
                        SUM(link) AS link,
                        SUM(louvor) AS louvor,
                        SUM(comunicacao) AS comunicacao,
                        SUM(voluntarios) AS voluntarios,
                        $pastores_count_expr
                    FROM presencas
                    WHERE YEAR(data) = :year
                      AND CEIL(MONTH(data)/3) = :quarter";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':year' => date('Y', strtotime($ref_date)), ':quarter' => $quarter]);
            break;

        case 'ano':
            $sql = "SELECT
                        SUM(adultos) AS adultos,
                        SUM(criancas_templo) AS criancas_templo,
                        SUM(kids_baby) AS kids_baby,
                        SUM(kids) AS kids,
                        SUM(link) AS link,
                        SUM(louvor) AS louvor,
                        SUM(comunicacao) AS comunicacao,
                        SUM(voluntarios) AS voluntarios,
                        $pastores_count_expr
                    FROM presencas
                    WHERE YEAR(data) = :year";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':year' => $year_input]);
            break;

        default:
            throw new Exception("Período inválido.");
    }

    $data_row = $stmt->fetch(PDO::FETCH_ASSOC);
    // garantir valores numéricos
    $adultos = (int)($data_row['adultos'] ?? 0);
    $criancas_templo = (int)($data_row['criancas_templo'] ?? 0);
    $kids_baby = (int)($data_row['kids_baby'] ?? 0);
    $kids = (int)($data_row['kids'] ?? 0);
    $link = (int)($data_row['link'] ?? 0);
    $louvor = (int)($data_row['louvor'] ?? 0);
    $comunicacao = (int)($data_row['comunicacao'] ?? 0);
    $voluntarios = (int)($data_row['voluntarios'] ?? 0);
    $pastores_count = (int)($data_row['pastores_count'] ?? 0);

    $total_pessoas = $adultos + $criancas_templo + $kids_baby + $kids;
    $total_voluntarios = $link + $louvor + $comunicacao + $voluntarios + $pastores_count;
    $total_geral = $total_pessoas + $total_voluntarios;

} catch (Exception $e) {
    $error = "Erro ao consultar dados: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Filtrar e visualizar - Presenças</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <style>
        body{font-family:Arial, sans-serif;margin:20px}
        form{max-width:900px;margin-bottom:20px}
        label{display:inline-block;margin-right:8px}
        .resumo{max-width:900px;background:#fafafa;padding:12px;border:1px solid #eee}
        .grid{display:flex;gap:20px;flex-wrap:wrap}
        .card{flex:1;min-width:200px}
    </style>
</head>
<body>
    <h1>Filtrar por período</h1>

    <form method="get" action="stats.php">
        <label for="period">Período:</label>
        <select id="period" name="period" onchange="onPeriodChange()" required>
            <option value="dia" <?= $period==='dia'?'selected':'' ?>>Dia</option>
            <option value="semana" <?= $period==='semana'?'selected':'' ?>>Semana</option>
            <option value="mes" <?= $period==='mes'?'selected':'' ?>>Mês</option>
            <option value="trimestre" <?= $period==='trimestre'?'selected':'' ?>>Trimestre</option>
            <option value="ano" <?= $period==='ano'?'selected':'' ?>>Ano</option>
        </select>

        <span id="input-date" style="margin-left:12px;">
            <label>Data referência:
                <input type="date" name="date" id="date" value="<?= htmlspecialchars($ref_date) ?>">
            </label>
        </span>

        <span id="input-month" style="display:none;margin-left:12px;">
            <label>Mês:
                <input type="month" name="month_year" id="month_year" value="<?= htmlspecialchars(date('Y-m', strtotime($year_input.'-'.$month_input.'-01'))) ?>">
            </label>
        </span>

        <span id="input-year" style="display:none;margin-left:12px;">
            <label>Ano:
                <input type="number" name="year" id="year" min="2000" max="2100" value="<?= htmlspecialchars($year_input) ?>">
            </label>
        </span>

        <button type="submit" style="margin-left:12px;">Aplicar</button>
    </form>

    <?php if ($error): ?>
        <div style="color:#a00"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="resumo">
        <h2>Resumo (<?= htmlspecialchars(ucfirst($period)) ?>)</h2>
        <div class="grid">
            <div class="card"><strong>Adultos:</strong> <?= $adultos ?></div>
            <div class="card"><strong>Crianças/Templo:</strong> <?= $criancas_templo ?></div>
            <div class="card"><strong>Kids Baby:</strong> <?= $kids_baby ?></div>
            <div class="card"><strong>Kids:</strong> <?= $kids ?></div>
            <div class="card"><strong>Link:</strong> <?= $link ?></div>
            <div class="card"><strong>Louvor:</strong> <?= $louvor ?></div>
            <div class="card"><strong>Comunicação:</strong> <?= $comunicacao ?></div>
            <div class="card"><strong>Voluntários Gerais:</strong> <?= $voluntarios ?></div>
            <div class="card"><strong>Pastores (contagem):</strong> <?= $pastores_count ?></div>
        </div>
        <p><strong>Total Pessoas:</strong> <?= $total_pessoas ?> &nbsp; | &nbsp; <strong>Total Voluntários:</strong> <?= $total_voluntarios ?> &nbsp; | &nbsp; <strong>Total Geral:</strong> <?= $total_geral ?></p>
    </div>

    <h2>Gráfico</h2>
    <canvas id="grafico" style="max-width:900px"></canvas>

    <script>
        // lógica para mostrar inputs conforme período
        function onPeriodChange(){
            const p = document.getElementById('period').value;
            document.getElementById('input-date').style.display = (p === 'dia' || p === 'semana' || p === 'trimestre') ? 'inline-block' : 'none';
            document.getElementById('input-month').style.display = (p === 'mes') ? 'inline-block' : 'none';
            document.getElementById('input-year').style.display = (p === 'ano' || p === 'mes') ? 'inline-block' : 'none';
        }
        onPeriodChange();

        // se o usuário escolheu month input, ao submeter convertemos para month e year GET params
        document.querySelector('form').addEventListener('submit', function(e){
            const p = document.getElementById('period').value;
            if(p === 'mes'){
                const m = document.getElementById('month_year').value; // formato YYYY-MM
                if(m){
                    const [y, mm] = m.split('-');
                    // criar campos hidden year e month
                    const f = this;
                    let inpY = document.createElement('input'); inpY.type='hidden'; inpY.name='year'; inpY.value = y; f.appendChild(inpY);
                    let inpM = document.createElement('input'); inpM.type='hidden'; inpM.name='month'; inpM.value = mm; f.appendChild(inpM);
                }
            }
            // para ano já existe input year
        });

        // Chart.js
        const ctx = document.getElementById('grafico').getContext('2d');
        const data = {
            labels: ['Adultos','Crianças/Templo','Kids Baby','Kids','Link','Louvor','Comunicação','Voluntários Gerais','Pastores'],
            datasets: [{
                data: [
                    <?= $adultos ?>, <?= $criancas_templo ?>, <?= $kids_baby ?>, <?= $kids ?>,
                    <?= $link ?>, <?= $louvor ?>, <?= $comunicacao ?>, <?= $voluntarios ?>, <?= $pastores_count ?>
                ],
                backgroundColor: [
                    'rgba(255,99,132,0.7)','rgba(54,162,235,0.7)','rgba(255,206,86,0.7)','rgba(75,192,192,0.7)',
                    'rgba(153,102,255,0.7)','rgba(255,159,64,0.7)','rgba(199,199,199,0.7)','rgba(0,128,128,0.7)','rgba(128,0,128,0.7)'
                ]
            }]
        };
        const grafico = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                plugins: {
                    datalabels: {
                        color: '#fff',
                        formatter: (value, ctx) => {
                            let total = ctx.chart.data.datasets[0].data.reduce((a,b)=>a+b,0);
                            if(total === 0) return '0%';
                            return (value/total*100).toFixed(1) + '%';
                        },
                        font: { weight: 'bold' }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
</body>
</html>