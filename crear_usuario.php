<?php
// Incluye tu conexión (ajusta el path si es necesario)
include("includes/conexion.php");

// DATOS DEL NUEVO USUARIO — CAMBIA ESTOS VALORES
$username = "admin";
$password = "admin123"; // Esto se va a hashear
$nombre_completo = "Administrador Principal";
$rol = "admin";

// Hasheamos la contraseña
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Preparamos el INSERT
    $sql = "INSERT INTO usuario (username, password, nombre_completo, rol)
            VALUES (:username, :password, :nombre_completo, :rol)";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hash);
    $stmt->bindParam(':nombre_completo', $nombre_completo);
    $stmt->bindParam(':rol', $rol);
    $stmt->execute();

    echo "✅ Usuario '$username' creado con éxito, ya puedes loguearte.";
} catch (PDOException $e) {
    if ($e->getCode() === '23505') {
        echo "⚠️ El usuario '$username' ya existe.";
    } else {
        echo "💥 Error al crear el usuario: " . $e->getMessage();
    }
}
?>
