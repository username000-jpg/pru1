<?php
session_start(); // Esto SIEMPRE va al inicio, antes de cualquier echo, HTML, espacios o lo que sea

$base_url = "/control_gastos/"; // Ajusta si el proyecto está en otra ruta

// Validar si el usuario está logueado
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Control de Gastos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="../dashboard.php">Control de Gastos</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="../ingresos/listar.php">Ingresos</a></li>
        <li class="nav-item"><a class="nav-link" href="../gastos/listar.php">Gastos</a></li>
        <li class="nav-item"><a class="nav-link" href="../categorias/listar.php">Categorías</a></li>
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador') { ?>
          <li class="nav-item"><a class="nav-link" href="../usuarios/listar.php">Usuarios</a></li>
        <?php } ?>
      </ul>
      <span class="navbar-text text-white me-3">Usuario: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
      <a href="../logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
  </div>
</nav>
<div class="container mt-4">
