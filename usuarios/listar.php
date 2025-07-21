<?php
session_start();
include("../includes/header.php");
include("../includes/conexion.php");

$stmt = $conexion->prepare("SELECT u.id_usuario, u.username, r.nombre_rol FROM usuario u 
                            INNER JOIN rol r ON u.id_rol = r.id_rol");
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Lista de Usuarios</h4>
<a href="registrar.php" class="btn btn-success mb-3">+ Nuevo Usuario</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Rol</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $user): ?>
            <tr>
                <td><?= $user['id_usuario'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= $user['nombre_rol'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include("../includes/footer.php"); ?>
