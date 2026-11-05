<?php
// PRIVADA: muestra lo que reclamó el usuario logueado (se guarda en Pedidos / Detalle_pedido)
include 'php/auth.php';
include 'php/conexion.php';

$items = llamar($conexion, "CALL sp_mis_productos(" . (int) $_SESSION['usuario_id'] . ")");

$total = 0;
foreach ($items as $it) { $total += $it['subtotal']; }
$ok = isset($_GET['ok']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mis productos | Neko-san a 5km</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

  <?php include 'php/nav.php'; ?>

  <header class="portada text-center">
    <div class="container">
      <h1>Mis productos 🐾</h1>
      <p>Todo lo que reclamaste queda guardado acá.</p>
    </div>
  </header>

  <main class="container py-5">

    <?php if ($ok) { ?>
      <div class="alert alert-success">¡Listo! Reclamaste el producto y se descontó del stock.</div>
    <?php } ?>

    <?php if (empty($items)) { ?>
      <p class="text-center">Todavía no reclamaste nada. <a href="tienda.php">Ir a la tienda</a></p>
    <?php } else { ?>
      <div class="row g-4">
        <?php foreach ($items as $it) { ?>
        <div class="col-md-4">
          <div class="card producto h-100">
            <?php if ($it['imagen'] != "") { ?>
              <img src="<?php echo htmlspecialchars(url_imagen($it['imagen'])); ?>" alt="<?php echo htmlspecialchars($it['nombre']); ?>">
            <?php } else { ?>
              <div class="imagen-producto">🐾</div>
            <?php } ?>
            <div class="card-body">
              <h5><?php echo htmlspecialchars($it['nombre']); ?></h5>
              <p class="mb-1">Cantidad: <strong><?php echo (int) $it['cantidad']; ?></strong></p>
              <p class="mb-1">Precio unitario: $<?php echo number_format($it['precio_unitario'], 0, ',', '.'); ?></p>
              <p class="precio">Subtotal: $<?php echo number_format($it['subtotal'], 0, ',', '.'); ?></p>
              <p class="mb-0"><small>Reclamado el <?php echo htmlspecialchars($it['Fecha_pedido']); ?> · Pedido #<?php echo (int) $it['id_pedido']; ?></small></p>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <h4 class="text-end mt-4 precio">Total reclamado: $<?php echo number_format($total, 0, ',', '.'); ?></h4>
    <?php } ?>

  </main>

  <footer class="pie text-center py-4">© 2026 Neko-san a 5km</footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
