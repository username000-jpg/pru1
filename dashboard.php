<?php
session_start();//if
include("includes/conexion.php");
include("includes/header.php");

$id_usuario = $_SESSION['id_usuario'];

// Totales
$sql_ing = "SELECT total_ingresos(:id)";
$stmt = $conexion->prepare($sql_ing);
$stmt->execute(['id' => $id_usuario]);
$total_ingresos = $stmt->fetchColumn();

$sql_gas = "SELECT total_gastos(:id)";
$stmt = $conexion->prepare($sql_gas);
$stmt->execute(['id' => $id_usuario]);
$total_gastos = $stmt->fetchColumn();

$balance = $total_ingresos - $total_gastos;


// Últimos movimientos (ingresos + gastos)
$sql = "(SELECT monto, descripcion, fecha, 'Ingreso' AS tipo FROM ingreso WHERE id_usuario = :id_usuario)
        UNION ALL
        (SELECT monto, descripcion, fecha, 'Gasto' AS tipo FROM gasto WHERE id_usuario = :id_usuario)
        ORDER BY fecha DESC LIMIT 5";

$stmt = $conexion->prepare($sql);
$stmt->execute([':id_usuario' => $id_usuario]);
$movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Datos para gráfico (últimos 7 días)
$labels = [];
$datos_ingresos = [];
$datos_gastos = [];

for ($i = 6; $i >= 0; $i--) {
    $fecha = date("Y-m-d", strtotime("-$i days"));
    $labels[] = $fecha;

    $ingreso = $conexion->query("SELECT COALESCE(SUM(monto),0) FROM ingreso WHERE id_usuario = $id_usuario AND fecha = '$fecha'")->fetchColumn();
    $gasto = $conexion->query("SELECT COALESCE(SUM(monto),0) FROM gasto WHERE id_usuario = $id_usuario AND fecha = '$fecha'")->fetchColumn();

    $datos_ingresos[] = $ingreso;
    $datos_gastos[] = $gasto;
}
?>
<h4>Dashboard</h4>

<div class="d-flex justify-content-end mb-3 gap-2">
  <form action="exportar/resumen_mensual.php" method="GET" target="_blank">
    <input type="hidden" name="mes" value="<?php echo date('m'); ?>">
    <input type="hidden" name="anio" value="<?php echo date('Y'); ?>">
    <button type="submit" class="btn btn-outline-primary">
      📄 Exportar Resumen Mensual
    </button>
  </form>
  
  <a href="exportar/balance_total.php" target="_blank" class="btn btn-outline-success">
    📊 Exportar Balance Total
  </a>
</div>


<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Total Ingresos</h5>
                <p class="fs-4">S/ <?php echo number_format($total_ingresos, 2); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5>Total Gastos</h5>
                <p class="fs-4">S/ <?php echo number_format($total_gastos, 2); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Balance</h5>
                <p class="fs-4">S/ <?php echo number_format($balance, 2); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Gráfico -->
<canvas id="grafico" height="100"></canvas>

<!-- Últimos movimientos -->
<h5 class="mt-4">Últimos movimientos</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Monto</th>
            <th>Descripción</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($movimientos as $mov): ?>
        <tr>
            <td><?php echo $mov['tipo']; ?></td>
            <td><?php echo number_format($mov['monto'], 2); ?></td>
            <td><?php echo htmlspecialchars($mov['descripcion']); ?></td>
            <td><?php echo $mov['fecha']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('grafico').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [
            {
                label: 'Ingresos',
                data: <?php echo json_encode($datos_ingresos); ?>,
                borderColor: 'green',
                backgroundColor: 'rgba(0,128,0,0.1)',
                fill: true
            },
            {
                label: 'Gastos',
                data: <?php echo json_encode($datos_gastos); ?>,
                borderColor: 'red',
                backgroundColor: 'rgba(255,0,0,0.1)',
                fill: true
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<?php include("includes/footer.php"); ?>
