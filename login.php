<?php
session_start();
include("includes/conexion.php"); // asegúrate que el archivo se llama así

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
        $_SESSION['rol'] = $usuario['id_rol']; // por si usas roles luego

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Usuario o contraseña incorrecta, causa.";
        // Aquí podrías redirigir o mostrar un error decente en HTML también
    }
}
?>
