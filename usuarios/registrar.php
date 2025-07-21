<?php
session_start();
include("../includes/header.php");
include("../includes/conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Encriptar contraseña
    $id_rol = $_POST["id_rol"];

    $stmt = $conexion->prepare("INSERT INTO usuario (username, password, id_rol) VALUES (:username, :password, :id_rol)");

    try {
        $stmt->execute([
            ":username" => $username,
            ":password" => $password,
            ":id_rol" => $id_rol
        ]);
        $mensaje = "Usuario registrado correctamente";
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}

// Obtener roles
$roles = $conexion->query("SELECT * FROM rol")->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Registrar Usuario</h4>
<p><?= $mensaje ?></p>

<form method="POST" class="col-md-6">
    <div class="mb-3">
        <label>Usuario</label>
        <input type="text" name="username" required class="form-control">
    </div>
    <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="password" required class="form-control">
    </div>
    <div class="mb-3">
        <label>Rol</label>
        <select name="id_rol" class="form-control" required>
            <option value="">Seleccione un rol</option>
            <?php foreach ($roles as $rol): ?>
                <option value="<?= $rol['id_rol'] ?>"><?= $rol['nombre_rol'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button class="btn btn-primary">Registrar</button>
</form>

<?php include("../includes/footer.php"); ?>
