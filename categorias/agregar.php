<?php
session_start();
include("../includes/header.php");
include("../includes/conexion.php");

$id_usuario = $_SESSION["id_usuario"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre_categoria"]);

    if (!empty($nombre)) {
        // Verifica que no exista ya para ese usuario
        $check = $conexion->prepare("SELECT 1 FROM categoria WHERE nombre_categoria = :nombre AND id_usuario = :id_usuario");
        $check->execute([':nombre' => $nombre, ':id_usuario' => $id_usuario]);

        if ($check->fetch()) {
            echo "<div class='alert alert-warning'>Esa categoría ya existe.</div>";
        } else {
            $sql = "INSERT INTO categoria (nombre_categoria, id_usuario)
                    VALUES (:nombre, :id_usuario)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':id_usuario' => $id_usuario
            ]);
            echo "<script>alert('Categoría agregada'); window.location='listar.php';</script>";
        }
    } else {
        echo "<div class='alert alert-danger'>Escribe un nombre válido</div>";
    }
}
?>

<h4>Agregar Categoría</h4>
<form method="POST" class="mb-3">
    <div class="mb-2">
        <label>Nombre de la Categoría</label>
        <input type="text" name="nombre_categoria" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="listar.php" class="btn btn-secondary">Volver</a>
</form>

<?php include("../includes/footer.php"); ?>
