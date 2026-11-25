<?php
// Cabecera comun: <head> + menu + avisos. Se incluye despues de require 'php/sesion.php'.
// Antes de incluirla, la pagina puede definir $titulo.
if (!isset($titulo)) { $titulo = "Neko-san a 5km"; }

$cant_carrito = 0;
if ($logueado) {
    $r = consulta($conexion, "CALL sp_contar_carrito(?)", "i", [$_SESSION['usuario_id']]);
    $cant_carrito = (int) ($r[0]['total'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo e($titulo); ?> | Neko-san a 5km</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <a class="navbar-brand" href="index.php">🐾 Neko-san a 5km</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="tienda.php">Tienda</a></li>

          <?php if ($logueado) { ?>
            <li class="nav-item">
              <a class="nav-link" href="carrito.php">🛒 Carrito
                <?php if ($cant_carrito > 0) { ?><span class="badge rounded-pill contador"><?php echo $cant_carrito; ?></span><?php } ?>
              </a>
            </li>
            <?php if ($es_admin) { ?>
              <li class="nav-item"><a class="nav-link" href="admin.php">⚙️ Administrar</a></li>
            <?php } ?>
            <li class="nav-item"><span class="nav-link saludo">Hola, <?php echo e($_SESSION['nombre_usuario']); ?></span></li>
            <li class="nav-item"><a class="btn btn-outline-danger btn-sm ms-lg-2" href="php/logout.php">Cerrar sesión</a></li>
          <?php } else { ?>
            <li class="nav-item"><a class="nav-link" href="registro.php">Registrate</a></li>
            <li class="nav-item"><a class="btn boton-rosa btn-sm ms-lg-2" href="login.php">Ingresar</a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>

  <?php if (!empty($_SESSION['flash'])) { ?>
    <div class="container mt-3">
      <div class="alert alert-<?php echo e($_SESSION['flash']['tipo']); ?> mb-0"><?php echo e($_SESSION['flash']['texto']); ?></div>
    </div>
    <?php unset($_SESSION['flash']); ?>
  <?php } ?>
