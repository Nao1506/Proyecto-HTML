<?php
// Página pública: primero la lógica (deja la lista en $productos), después el HTML
include 'php/logica_catalogo.php';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda | Neko-san a 5km</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

  <?php include 'php/nav.php'; ?>

  <header class="portada text-center">
    <div class="container">
      <h1>Tienda</h1>
      <p>Productos publicados por la comunidad.</p>
    </div>
  </header>

  <main class="container py-5">

    <?php if ($error == "stock") { ?>
      <div class="alert alert-warning">No alcanzaba el stock para esa cantidad. Probá con menos unidades.</div>
    <?php } elseif ($error == "datos") { ?>
      <div class="alert alert-danger">Cantidad inválida.</div>
    <?php } ?>

    <?php if (empty($productos)) { ?>
      <p class="text-center">Todavía no hay productos con stock disponible.</p>
    <?php } ?>

    <div class="row g-4">

      <?php foreach ($productos as $p) { ?>
      <div class="col-md-4">
        <div class="card producto h-100">

          <?php if ($p['imagen'] != "") { ?>
            <img src="<?php echo htmlspecialchars(url_imagen($p['imagen'])); ?>" alt="<?php echo htmlspecialchars($p['nombre']); ?>">
          <?php } else { ?>
            <div class="imagen-producto">🐾</div>
          <?php } ?>

          <div class="card-body">
            <h5><?php echo htmlspecialchars($p['nombre']); ?></h5>
            <p><?php echo htmlspecialchars($p['descripcion']); ?></p>
            <p class="precio">$<?php echo number_format($p['Precio_actual'], 0, ',', '.'); ?></p>
            <p class="mb-1"><small>Categoría: <?php echo htmlspecialchars($p['categoria']); ?></small></p>
            <p><strong>Stock: <?php echo (int) $p['stock_general']; ?></strong></p>

            <?php if ($logueado) { ?>
              <!-- Formulario POST para reclamar -->
              <form action="php/logica_reclamar.php" method="POST">
                <input type="hidden" name="id_producto" value="<?php echo (int) $p['id_producto']; ?>">
                <input class="form-control mb-2" type="number" name="num_cantidad" value="1" min="1" max="<?php echo (int) $p['stock_general']; ?>">
                <button class="btn boton-rosa w-100" type="submit">Reclamar</button>
              </form>
            <?php } else { ?>
              <a href="login.php?aviso=1" class="btn boton-rosa w-100">Ingresá para reclamar</a>
            <?php } ?>
          </div>

        </div>
      </div>
      <?php } ?>

    </div>
  </main>

  <footer class="pie text-center py-4">© 2026 Neko-san a 5km</footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
