global $DB;
<?php
/**
 * Dashboard de resultados da pesquisa de satisfação
 */

include ('../../../inc/includes.php');
global $DB, $CFG_GLPI;

// Controle de permissão: apenas administradores e gestores
Session::checkRight('config', READ);
$user_profile = $_SESSION['glpiactiveprofile']['name'];
$allowed_profiles = ['Administrador', 'Gestor', 'Super-Admin'];
if (!in_array($user_profile, $allowed_profiles)) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Acesso restrito. Você não tem permissão para visualizar este painel.</div></div>';
    exit;
}

// Filtros avançados
$filter_sql = '';
$min_rating = isset($_GET['min_rating']) ? intval($_GET['min_rating']) : '';
$date_start = isset($_GET['date_start']) ? $_GET['date_start'] : '';
$date_end = isset($_GET['date_end']) ? $_GET['date_end'] : '';
if ($min_rating) {
    $filter_sql .= " AND rating >= $min_rating ";
}
if ($date_start && $date_end) {
    $date_start_sql = $DB->escape($date_start);
    $date_end_sql = $DB->escape($date_end);
    $filter_sql .= " AND date_answered BETWEEN '$date_start_sql' AND '$date_end_sql' ";
}

// Função para registrar logs de auditoria
function log_auditoria($acao) {
    $user = $_SESSION['glpiname'] ?? 'desconhecido';
    $data = date('Y-m-d H:i:s');
    $linha = "$data | $user | $acao\n";
    file_put_contents(__DIR__ . '/../audit.log', $linha, FILE_APPEND);
}

// Log de acesso ao dashboard
log_auditoria('Acesso ao dashboard');

// Exportação CSV
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    log_auditoria('Exportação CSV');
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=satisfactionsurvey.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Chamado', 'Nota', 'Comentário', 'Data']);
    $csv_query = "SELECT * FROM glpi_plugin_satisfactionsurvey_responses WHERE 1 $filter_sql ORDER BY date_answered DESC";
    $csv_result = $DB->query($csv_query);
    while ($row = $DB->fetch_assoc($csv_result)) {
        fputcsv($output, [
            $row['tickets_id'],
            $row['rating'],
            $row['comment'],
            $row['date_answered']
        ]);
    }
    fclose($output);
    exit;
}

// Consulta respostas
$query = "SELECT * FROM glpi_plugin_satisfactionsurvey_responses WHERE 1 $filter_sql ORDER BY date_answered DESC";
$result = $DB->query($query);

// Estatísticas
$stats_query = "SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM glpi_plugin_satisfactionsurvey_responses WHERE 1 $filter_sql";
$stats = $DB->query($stats_query);
$stats_row = $DB->fetch_assoc($stats);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Resultados da Pesquisa de Satisfação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Resultados da Pesquisa de Satisfação</h2>
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Nota mínima</label>
            <select name="min_rating" class="form-select">
                <option value="">Todas</option>
                <option value="1" <?php if($min_rating==1) echo 'selected'; ?>>1</option>
                <option value="2" <?php if($min_rating==2) echo 'selected'; ?>>2</option>
                <option value="3" <?php if($min_rating==3) echo 'selected'; ?>>3</option>
                <option value="4" <?php if($min_rating==4) echo 'selected'; ?>>4</option>
                <option value="5" <?php if($min_rating==5) echo 'selected'; ?>>5</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Data inicial</label>
            <input type="date" name="date_start" class="form-control" value="<?php echo htmlspecialchars($date_start); ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Data final</label>
            <input type="date" name="date_end" class="form-control" value="<?php echo htmlspecialchars($date_end); ?>">
        </div>
        <div class="col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="?export=csv&min_rating=<?php echo $min_rating; ?>&date_start=<?php echo $date_start; ?>&date_end=<?php echo $date_end; ?>" class="btn btn-success">Exportar CSV</a>
        </div>
    </form>
    <div class="mb-3">
        <strong>Média de satisfação:</strong> <?php echo round($stats_row['avg_rating'], 2); ?> / 5<br>
        <strong>Total de respostas:</strong> <?php echo $stats_row['total']; ?>
    </div>
    <div class="mb-4">
        <canvas id="chartSatisfacao" height="120"></canvas>
    </div>
    <?php
    // Dados para gráfico
    $chart_data = [1=>0,2=>0,3=>0,4=>0,5=>0];
    $chart_query = "SELECT rating, COUNT(*) as total FROM glpi_plugin_satisfactionsurvey_responses WHERE 1 $filter_sql GROUP BY rating";
    $chart_result = $DB->query($chart_query);
    while ($row = $DB->fetch_assoc($chart_result)) {
        $chart_data[intval($row['rating'])] = intval($row['total']);
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    const ctx = document.getElementById('chartSatisfacao').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Péssimo','Ruim','Regular','Bom','Excelente'],
            datasets: [{
                label: 'Quantidade de respostas',
                data: [<?php echo implode(',', $chart_data); ?>],
                backgroundColor: [
                    '#dc3545','#fd7e14','#ffc107','#0d6efd','#198754'
                ],
                borderRadius: 8
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Distribuição das avaliações',
                    font: { size: 18 }
                }
            },
            scales: {
                y: { beginAtZero: true, stepSize: 1 }
            }
        }
    });
    </script>
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>Chamado</th>
                <th class="text-center">Nota</th>
                <th>Comentário</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $DB->fetch_assoc($result)) { 
            $rating = intval($row['rating']);
            $ratingClass = $rating <= 2 ? 'text-danger' : ($rating == 3 ? 'text-warning' : 'text-success');
            $emoji = ['&#128545;','&#128542;','&#128528;','&#128522;','&#128525;'][$rating-1];
        ?>
            <tr>
                <td><span class="badge bg-primary">#<?php echo $row['tickets_id']; ?></span></td>
                <td class="text-center <?php echo $ratingClass; ?>" style="font-size:1.5rem;"> <?php echo $emoji; ?> <span class="ms-2 fw-bold"> <?php echo $rating; ?> </span></td>
                <td><?php echo htmlspecialchars($row['comment']); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($row['date_answered'])); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
