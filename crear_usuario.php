<?php
include("includes/conexion.php");

$username = "pru23";
$password = "pru23";
$hash = password_hash($password, PASSWORD_DEFAULT);
$id_rol = 1;

$sql = "INSERT INTO usuario (username, password, id_rol) VALUES (:username, :password, :id_rol)";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hash);
$stmt->bindParam(':id_rol', $id_rol);

if ($stmt->execute()) {
    echo "Usuario creado con éxito con hash: $hash";
} else {
    echo "Error al crear el usuario";
}
