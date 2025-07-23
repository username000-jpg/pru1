<?php
include("includes/conexion.php");

$sql = "SELECT * FROM usuario";
$stmt = $conexion->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($usuarios);
echo "</pre>";
