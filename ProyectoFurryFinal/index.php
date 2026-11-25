<?php
require_once __DIR__ . '/php/sesion.php';

// Los 3 productos más nuevos de la tienda
$todos = consulta($conexion, "CALL sp_listar_productos()");
$destacados = array_slice($todos, 0, 3);

$titulo = "Inicio";
include __DIR__ . '/php/cabecera.php';
?>

  <!-- Presentación -->
  <header class="portada text-center">
    <div class="container">
      <h1>Bienvenido a Neko-san a 5km 💗</h1>
      <p>Encuentra accesorios y productos para tu waifu interior.</p>
      <a href="tienda.php" class="btn boton-rosa">Ir a la tienda</a>
    </div>
  </header>

  <!-- Productos destacados (los últimos publicados) -->
  <main class="container py-5">
    <h2 class="text-center mb-4">Últimos productos publicados</h2>
    <div class="row g-4">

      <?php if (count($destacados) == 0) { ?>
        <p class="text-center">Todavía no hay productos. ¡Sé el primero en <a href="tienda.php">publicar uno</a>!</p>
      <?php } ?>

      <?php foreach ($destacados as $p) { ?>
      <div class="col-md-4">
        <div class="card producto h-100">
          <?php if ($p['imagen'] != "") { ?>
            <img src="<?php echo e(ruta_imagen($p['imagen'])); ?>" alt="<?php echo e($p['nombre']); ?>">
          <?php } else { ?>
            <div class="imagen-producto">🐾</div>
          <?php } ?>
          <div class="card-body">
            <h5 class="card-title"><?php echo e($p['nombre']); ?></h5>
            <p class="card-text"><?php echo e($p['descripcion']); ?></p>
            <p class="precio">$<?php echo number_format($p['Precio_actual'], 0, ',', '.'); ?></p>
            <a href="tienda.php" class="btn boton-rosa">Ver en la tienda</a>
          </div>
        </div>
      </div>
      <?php } ?>

    </div>
  </main>

<?php include __DIR__ . '/php/pie.php'; ?>
