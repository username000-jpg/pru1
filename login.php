<?php
session_start();
include("includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuario WHERE username = :username";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {
        $_SESSION['username'] = $usuario['username'];
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['rol'] = $usuario['rol']; // ¡Esta es la clave buena, causa!

        header("Location: dashboard.php");
        exit();
    } else {
        // Mejor no usar echo aquí para evitar romper el header
        header("Location: login.php?error=1");
        exit();
    }
}
?>
