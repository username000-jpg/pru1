include("includes/conexion.php");

$username = "pru23";
$password = "pru23";
$hash = password_hash($password, PASSWORD_DEFAULT);
$nombre_completo = "Prueba 23";
$rol = "admin"; // o "usuario"

$sql = "INSERT INTO usuario (username, password, nombre_completo, rol) 
        VALUES (:username, :password, :nombre_completo, :rol)";

$stmt = $conexion->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $hash);
$stmt->bindParam(':nombre_completo', $nombre_completo);
$stmt->bindParam(':rol', $rol);

if ($stmt->execute()) {
    echo "Usuario creado con éxito con hash: $hash";
} else {
    echo "Error al crear el usuario";
}
