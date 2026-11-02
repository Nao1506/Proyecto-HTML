<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda | Neko-san a 5km</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- OJO: esta vista se carga desde /php/, por eso las rutas empiezan con ../ -->
  <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <a class="navbar-brand" href="../index.html">🐾 Neko-san a 5km</a>
      <ul class="navbar-nav ms-auto flex-row gap-3">
        <li class="nav-item"><a class="nav-link" href="../index.html">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="../principal.php">Mi panel</a></li>
        <li class="nav-item"><a class="nav-link" href="../login.html">Ingresar</a></li>
      </ul>
    </div>
  </nav>

  <header class="portada text-center">
    <div class="container">
      <h1>Tienda</h1>
      <p>Productos publicados por la comunidad.</p>
    </div>
  </header>

  <main class="container py-5">
    <div class="row g-4">

      <?php foreach ($productos as $p) { ?>
      <div class="col-md-4">
        <div class="card producto h-100">

          <?php if ($p['imagen'] != "") { ?>
            <img src="../Img/<?php echo $p['imagen']; ?>" alt="<?php echo htmlspecialchars($p['nombre']); ?>">
          <?php } else { ?>
            <div class="imagen-producto">🐾</div>
          <?php } ?>

          <div class="card-body">
            <h5><?php echo htmlspecialchars($p['nombre']); ?></h5>
            <p><?php echo htmlspecialchars($p['descripcion']); ?></p>
            <p class="precio">$<?php echo number_format($p['Precio_actual'], 0, ',', '.'); ?></p>
            <p class="mb-1"><small>Categoría: <?php echo htmlspecialchars($p['categoria']); ?></small></p>
            <p><strong>Stock: <?php echo $p['stock_general']; ?></strong></p>

            <!-- Formulario POST para reclamar -->
            <form action="logica_reclamar.php" method="POST">
              <input type="hidden" name="id_producto" value="<?php echo $p['id_producto']; ?>">
              <input class="form-control mb-2" type="number" name="num_cantidad" value="1" min="1" max="<?php echo $p['stock_general']; ?>">
              <button class="btn boton-rosa w-100" type="submit">Reclamar</button>
            </form>
          </div>

        </div>
      </div>
      <?php } ?>

    </div>
  </main>

  <footer class="pie text-center py-4">© 2026 Neko-san a 5km</footer>
</body>
</html>
