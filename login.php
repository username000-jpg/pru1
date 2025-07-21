<?php
session_start();
include("includes/conexion.php");

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT u.*, r.nombre_rol FROM usuario u 
        INNER JOIN rol r ON u.id_rol = r.id_rol 
        WHERE username = :username";

$stmt = $conexion->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($password, $usuario['password'])) {
    // Login válido
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['username'] = $usuario['username'];
    $_SESSION['rol'] = $usuario['nombre_rol'];
    header("Location: dashboard.php");
    exit();
} else {
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location='index.php';</script>";
}
?>
