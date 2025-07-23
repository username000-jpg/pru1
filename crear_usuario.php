<?php
// Incluye la conexión a tu BD (ajusta el path si es necesario)
include("includes/conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recolectamos los datos del formulario
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $nombre_completo = trim($_POST['nombre_completo']);
    $rol = trim($_POST['rol']);

    // Validaciones básicas
    if (empty($username) || empty($password) || empty($nombre_completo)) {
        echo "❌ Todos los campos son obligatorios.";
        exit;
    }

    // Hasheamos la contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Preparamos el insert
        $sql = "INSERT INTO usuario (username, password, nombre_completo, rol) 
                VALUES (:username, :password, :nombre_completo, :rol)";
        $stmt = $conexion->prepare($sql);

        // Bindeamos
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':nombre_completo', $nombre_completo);
        $stmt->bindParam(':rol', $rol);

        // Ejecutamos
        $stmt->execute();

        echo "✅ Usuario creado correctamente. Ya puedes loguearte, máquina.";
    } catch (PDOException $e) {
        if ($e->getCode() == '23505') {
            echo "⚠️ El nombre de usuario ya existe, busca otro.";
        } else {
            echo "💥 Error en la BD: " . $e->getMessage();
        }
    }
} else {
    echo "⛔ Método no permitido.";
}
?>
