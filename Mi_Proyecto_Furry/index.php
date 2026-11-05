<?php include 'php/sesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Neko-san a 5km</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Tu CSS -->
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

  <?php include 'php/nav.php'; ?>

  <!-- Presentación -->
  <header class="portada text-center">
    <div class="container">
      <h1>Bienvenido a Neko-san a 5km 💗</h1>
      <p>Encuentra accesorios y productos para tu waifu interior.</p>
      <?php if ($logueado) { ?>
        <p class="fw-bold">¡Hola, <?php echo htmlspecialchars($nombre_sesion); ?>! 💗 Tu sesión está iniciada.</p>
        <a href="tienda.php" class="btn boton-rosa me-2">Ir a la tienda</a>
        <a href="mis_productos.php" class="btn btn-outline-secondary me-2">Mis productos</a>
      <?php } else { ?>
        <a href="login.php" class="btn btn-outline-secondary me-2">Ingresar</a>
      <?php } ?>
      <a href="#productos" class="btn boton-rosa">Ver productos</a>
    </div>
  </header>

  <!-- Productos destacados -->
  <main class="container py-5" id="productos">
    <h2 class="text-center mb-4">Productos destacados</h2>
    <div class="row g-4">

      <div class="col-md-4">
        <div class="card producto h-100">
        <img src="Img/zorra.jpg" alt="Descripción de la imagen">

          <div class="card-body">
            <h5 class="card-title">Cola de zorrito pop</h5>
            <p class="card-text">Una cola suave para completar tu estilo.</p>
            <p class="precio">$15.000</p>
            <a href="colas.php" class="btn boton-rosa">Ver producto</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card producto h-100">
          <img src="Img/Coneja.jpg" alt="Descripción de la imagen">
          <div class="card-body">
            <h5 class="card-title">Orejas de conejito pop</h5>
            <p class="card-text">Un accesorio adorable y divertido (No incluye la peluca).</p>
            <p class="precio">$10.000</p>
            <a href="orejas.php" class="btn boton-rosa">Ver producto</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card producto h-100">
        <img src="Img/images.jpg" alt="Descripción de la imagen">
          <div class="card-body">
            <h5 class="card-title">Traje furryto pop</h5>
            <p class="card-text">Un diseño para darle vida a tu personaje.</p>
            <p class="precio">Consultar precio</p>
            <a href="trajes.php" class="btn boton-rosa">Ver producto</a>
          </div>
        </div>
      </div>

    </div>
  </main>

  <footer class="pie text-center py-4">
    <p class="mb-0">© 2026 Neko-san a 5km 💗</p>
  </footer>

  <!-- Bootstrap para el menú adaptable; no es código JavaScript propio -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
