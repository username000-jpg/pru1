<?php
session_start();
include("../includes/header.php");
include("../includes/conexion.php");

$id_usuario = $_SESSION["id_usuario"];

// Obtener categorías del usuario
$sql = "SELECT * FROM categoria WHERE id_usuario = :id_usuario ORDER BY nombre_categoria ASC";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(":id_usuario", $id_usuario);
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Mis Categorías</h4>
<a href="agregar.php" class="btn btn-success mb-3">+ Nueva Categoría</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Nombre</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categorias as $cat): ?>
        <tr>
            <td><?php echo htmlspecialchars($cat['nombre_categoria']); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include("../includes/footer.php"); ?>
