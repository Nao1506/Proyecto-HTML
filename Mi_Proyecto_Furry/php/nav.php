<?php
// Menú compartido por todas las páginas de la raíz del proyecto.
// Muestra "Ingresar / Registrate" o el nombre del usuario según haya sesión.
include_once __DIR__ . '/sesion.php';
?>
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <a class="navbar-brand" href="index.php">🐾 Neko-san a 5km</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="colas.php">Colas</a></li>
          <li class="nav-item"><a class="nav-link" href="orejas.php">Orejas</a></li>
          <li class="nav-item"><a class="nav-link" href="trajes.php">Trajes</a></li>
          <li class="nav-item"><a class="nav-link" href="accesorios.php">Accesorios</a></li>
          <li class="nav-item"><a class="nav-link" href="tienda.php">Tienda</a></li>
          <?php if ($logueado) { ?>
            <li class="nav-item"><a class="nav-link" href="mis_productos.php">Mis productos</a></li>
            <li class="nav-item"><a class="nav-link" href="principal.php">Mi panel</a></li>
            <li class="nav-item"><span class="nav-link fw-bold">🐾 <?php echo htmlspecialchars($nombre_sesion); ?></span></li>
            <li class="nav-item"><a class="nav-link" href="php/logout.php">Cerrar sesión</a></li>
          <?php } else { ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Ingresar</a></li>
            <li class="nav-item"><a class="nav-link" href="registro.php">Registrate</a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>
