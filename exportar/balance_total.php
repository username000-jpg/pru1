<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;

session_start();
include("../includes/conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$total_ingresos = $conexion->query("SELECT COALESCE(SUM(monto),0) FROM ingreso WHERE id_usuario = $id_usuario")->fetchColumn();
$total_gastos = $conexion->query("SELECT COALESCE(SUM(monto),0) FROM gasto WHERE id_usuario = $id_usuario")->fetchColumn();
$balance = $total_ingresos - $total_gastos;

$html = "
<h2>Balance General</h2>
<table border='1' cellspacing='0' cellpadding='5'>
<tr><th>Concepto</th><th>Monto (S/)</th></tr>
<tr><td>Total Ingresos</td><td>" . number_format($total_ingresos, 2) . "</td></tr>
<tr><td>Total Gastos</td><td>" . number_format($total_gastos, 2) . "</td></tr>
<tr><td><strong>Balance</strong></td><td><strong>" . number_format($balance, 2) . "</strong></td></tr>
</table>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$dompdf->stream("balance_total.pdf");
