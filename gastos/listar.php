<?php
session_start();
include("../includes/header.php");
include("../includes/conexion.php");

$id_usuario = $_SESSION["id_usuario"];

// Filtros
$fecha_inicio = $_GET["fecha_inicio"] ?? "";
$fecha_fin = $_GET["fecha_fin"] ?? "";
$id_categoria = $_GET["id_categoria"] ?? "";
$metodo_pago = $_GET["metodo_pago"] ?? "";

// Categorías del usuario
$categorias = $conexion->prepare("SELECT * FROM categoria WHERE id_usuario = :id_usuario");
$categorias->execute(["id_usuario" => $id_usuario]);
$categorias = $categorias->fetchAll(PDO::FETCH_ASSOC);

// Query dinámica
$sql = "SELECT g.*, c.nombre_categoria FROM gasto g 
        LEFT JOIN categoria c ON g.id_categoria = c.id_categoria 
        WHERE g.id_usuario = :id_usuario";

$params = [":id_usuario" => $id_usuario];

if ($fecha_inicio) {
    $sql .= " AND g.fecha >= :fecha_inicio";
    $params[":fecha_inicio"] = $fecha_inicio;
}
if ($fecha_fin) {
    $sql .= " AND g.fecha <= :fecha_fin";
    $params[":fecha_fin"] = $fecha_fin;
}
if ($id_categoria) {
    $sql .= " AND g.id_categoria = :id_categoria";
    $params[":id_categoria"] = $id_categoria;
}
if ($metodo_pago) {
    $sql .= " AND g.metodo_pago = :metodo_pago";
    $params[":metodo_pago"] = $metodo_pago;
}

$sql .= " ORDER BY g.fecha DESC";
$stmt = $conexion->prepare($sql);
$stmt->execute($params);
$gastos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Gastos</h4>
<a href="registrar.php" class="btn btn-primary mb-3">+ Nuevo Gasto</a>

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
    <div class="col-md-3">
        <label>Método de Pago</label>
        <select name="metodo_pago" class="form-control">
            <option value="">Todos</option>
            <?php foreach (['Efectivo', 'Yape', 'Plin', 'Transferencia'] as $mp): ?>
                <option value="<?= $mp; ?>" <?= $mp == $metodo_pago ? 'selected' : '' ?>><?= $mp; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-12 mt-2">
        <button class="btn btn-dark">Filtrar</button>
        <a href="listar.php" class="btn btn-secondary">Limpiar</a>
    </div>
</form>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Monto (S/)</th>
            <th>Descripción</th>
            <th>Categoría</th>
            <th>Método de pago</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($gastos as $gasto): ?>
        <tr>
            <td><?= number_format($gasto['monto'], 2); ?></td>
            <td><?= htmlspecialchars($gasto['descripcion']); ?></td>
            <td><?= $gasto['nombre_categoria']; ?></td>
            <td><?= $gasto['metodo_pago']; ?></td>
            <td><?= $gasto['fecha']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include("../includes/footer.php"); ?>
