<?php
session_start();
include("../includes/header.php");
include("../includes/conexion.php");

$id_usuario = $_SESSION["id_usuario"];

// Obtener categorías del usuario
$sql = "SELECT * FROM categoria WHERE id_usuario = :id_usuario ORDER BY nombre_categoria";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(":id_usuario", $id_usuario);
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $monto = $_POST["monto"];
    $descripcion = $_POST["descripcion"];
    $fecha = $_POST["fecha"];
    $metodo_pago = $_POST["metodo_pago"];
    $id_categoria = $_POST["id_categoria"];

    if (is_numeric($monto) && $monto > 0 && $id_categoria) {
        $sql = "INSERT INTO gasto (monto, descripcion, fecha, metodo_pago, id_categoria, id_usuario)
                VALUES (:monto, :descripcion, :fecha, :metodo_pago, :id_categoria, :id_usuario)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':monto' => $monto,
            ':descripcion' => $descripcion,
            ':fecha' => $fecha,
            ':metodo_pago' => $metodo_pago,
            ':id_categoria' => $id_categoria,
            ':id_usuario' => $id_usuario
        ]);

        echo "<script>alert('Gasto registrado'); window.location='listar.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Datos inválidos</div>";
    }
}
?>

<h4>Registrar Gasto</h4>
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
            <option value="">Selecciona una categoría</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?php echo $cat['id_categoria']; ?>"><?php echo $cat['nombre_categoria']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-2">
        <label>Método de pago</label>
        <select name="metodo_pago" class="form-control" required>
            <option value="Efectivo">Efectivo</option>
            <option value="Yape">Yape</option>
            <option value="Plin">Plin</option>
            <option value="Transferencia">Transferencia</option>
        </select>
    </div>
    <button type="submit" class="btn btn-danger">Guardar</button>
    <a href="listar.php" class="btn btn-secondary">Volver</a>
</form>

<?php include("../includes/footer.php"); ?>
