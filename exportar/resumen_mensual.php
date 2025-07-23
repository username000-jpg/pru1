<?php
require '../dompdf/autoload.inc.php';  // ajusta la ruta si es necesario
use Dompdf\Dompdf;

session_start();
include("../includes/conexion.php");

$id_usuario = $_SESSION['id_usuario'];
$mes = intval($_GET['mes'] ?? date("n"));   // date("n") = sin 0 adelante
$anio = intval($_GET['anio'] ?? date("Y"));


//obtener 
$sql = "SELECT * FROM obtener_resumen_mensual(:id_usuario, :mes, :anio)";
$stmt = $conexion->prepare($sql);
$stmt->execute([
    'id_usuario' => $id_usuario,
    'mes' => $mes,
    'anio' => $anio
]);
$resumen = $stmt->fetchAll(PDO::FETCH_ASSOC);


// HTML para el PDF
$html = "<h2>Resumen Mensual - $mes/$anio</h2>
<table border='1' cellspacing='0' cellpadding='5'>
<tr><th>Tipo</th><th>Monto</th><th>Descripción</th><th>Fecha</th></tr>";

foreach ($resumen as $r) {
    $html .= "<tr>
        <td>{$r['tipo']}</td>
        <td>S/ " . number_format($r['monto'], 2) . "</td>
        <td>" . htmlspecialchars($r['descripcion']) . "</td>
        <td>{$r['fecha']}</td>
    </tr>";
}

$html .= "</table>";


// PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("resumen_mensual_$mes-$anio.pdf");
