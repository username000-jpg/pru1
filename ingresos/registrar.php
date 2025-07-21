<?php
session_start();
include("../includes/header.php");
include("../includes/conexion.php");

$id_usuario = $_SESSION["id_usuario"];

// Obtener categorías disponibles para el usuario
$stmt_cat = $conexion->prepare("SELECT * FROM categoria WHERE id_usuario = :id_usuario");
$stmt_cat->execute(['id_usuario' => $id_usuario]);
$categorias = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $monto = $_POST["monto"];
    $descripcion = $_POST["descripcion"];
    $fecha = $_POST["fecha"];
    $id_categoria = $_POST["id_categoria"];

    if (is_numeric($monto) && $monto > 0 && is_numeric($id_categoria)) {
        $sql = "INSERT INTO ingreso (monto, descripcion, fecha, id_categoria, id_usuario)
                VALUES (:monto, :descripcion, :fecha, :id_categoria, :id_usuario)";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':monto', $monto);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':id_categoria', $id_categoria);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();

        echo "<script>alert('Ingreso registrado'); window.location='listar.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Datos inválidos</div>";
    }
}
?>

<h4>Registrar Ingreso</h4>
<form method="POST" class="mb-3">
    <div class="mb-2">
        <label>Monto (S/)</label>
        <input type="number" name="monto" step="0.01" class="form-control" required>
    </div>
    <div class="mb-2">
        <label>Descripción</label>
        <input type="text" name="descripcion" class="form-control">
    </div>
    <div class="mb-2">
        <label>Fecha</label>
        <input type="date" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
    </div>
    <div class="mb-2">
        <label>Categoría</label>
        <select name="id_categoria" class="form-control" required>
            <option value="">Seleccione una</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id_categoria']; ?>"><?= htmlspecialchars($cat['nombre_categoria']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="listar.php" class="btn btn-secondary">Volver</a>
</form>

<?php include("../includes/footer.php"); ?>
