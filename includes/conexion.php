<?php
// Datos de conexión a PostgreSQL
$host = "localhost";
$port = "5432";
$dbname = "control_gastos";
$user = "postgres";
$password = "123456"; // CAMBIA esto por tu contraseña real

try {
    $conexion = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    // Establecer el modo de error de PDO a excepción
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Conexión exitosa";
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit();
}
?>
