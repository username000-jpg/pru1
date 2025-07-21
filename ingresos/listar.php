<?php
session_start();
include("../includes/header.php");
include("../includes/conexion.php");

$id_usuario = $_SESSION["id_usuario"];

// Filtros
$fecha_inicio = $_GET["fecha_inicio"] ?? "";
$fecha_fin = $_GET["fecha_fin"] ?? "";
$id_categoria = $_GET["id_categoria"] ?? "";

// Categorías
$categorias = $conexion->prepare("SELECT * FROM categoria WHERE id_usuario = :id_usuario");
$categorias->execute(["id_usuario" => $id_usuario]);
$categorias = $categorias->fetchAll(PDO::FETCH_ASSOC);

// Query 
$sql = "SELECT i.*, c.nombre_categoria FROM ingreso i 
        LEFT JOIN categoria c ON i.id_categoria = c.id_categoria 
        WHERE i.id_usuario = :id_usuario";

$params = [":id_usuario" => $id_usuario];

if ($fecha_inicio) {
    $sql .= " AND i.fecha >= :fecha_inicio";
    $params[":fecha_inicio"] = $fecha_inicio;
}
if ($fecha_fin) {
    $sql .= " AND i.fecha <= :fecha_fin";
    $params[":fecha_fin"] = $fecha_fin;
}
if ($id_categoria) {
    $sql .= " AND i.id_categoria = :id_categoria";
    $params[":id_categoria"] = $id_categoria;
}

$sql .= " ORDER BY i.fecha DESC";
$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$ingresos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Ingresos</h4>
<a href="registrar.php" class="btn btn-primary mb-3">+ Nuevo Ingreso</a>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <label>Desde</label>
        <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio; ?>">
    </div>
    <div class="col-md-3">
        <label>Hasta</label>
        <input type="date" name="fecha_fin" class="form-control" value="<?php echo $fecha_fin; ?>">
    </div>
    <div class="col-md-3">
        <label>Categoría</label>
        <select name="id_categoria" class="form-control">
            <option value="">Todas</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id_categoria']; ?>" <?= $cat['id_categoria'] == $id_categoria ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nombre_categoria']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-dark me-2">Filtrar</button>
        <a href="listar.php" class="btn btn-secondary">Limpiar</a>
    </div>
</form>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Monto (S/)</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ingresos as $ingreso): ?>
        <tr>
            <td><?= number_format($ingreso['monto'], 2); ?></td>
            <td><?= htmlspecialchars($ingreso['descripcion']); ?></td>
            <td><?= $ingreso['nombre_categoria']; ?></td>
            <td><?= $ingreso['fecha']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include("../includes/footer.php"); ?>
