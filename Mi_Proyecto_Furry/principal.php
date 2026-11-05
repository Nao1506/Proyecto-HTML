<?php
include 'php/auth.php'; // ¡PROTEGIDO! Solo entra si hay sesión
$ok    = isset($_GET['ok']);
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Control</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

  <?php include 'php/nav.php'; ?>

  <h2 class="text-center mt-4">¡Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?>! 🐾</h2>

  <div class="card producto p-4" style="max-width: 450px; margin: 20px auto;">
    <h3>Subir un producto</h3>
    <?php if ($ok) { ?>
      <div class="alert alert-success">¡Producto publicado!</div>
    <?php } elseif ($error == "datos") { ?>
      <div class="alert alert-danger">Revisá los datos: nombre, stock (mínimo 1) y precio.</div>
    <?php } ?>
    <!-- enctype es obligatorio para poder enviar archivos -->
    <form action="php/guardar.php" method="POST" enctype="multipart/form-data">
      <label>Nombre:</label>
      <input class="form-control mb-2" type="text" name="txt_nombre" maxlength="150" required>

      <label>Descripción:</label>
      <input class="form-control mb-2" type="text" name="txt_desc" maxlength="100" required>

      <label>Categoría:</label>
      <select class="form-select mb-2" name="sel_categoria">
        <option value="1">Accesorios furry</option>
        <option value="2">Fursuits y disfraces</option>
        <option value="3">Peluches y coleccionables</option>
      </select>

      <label>Stock (cantidad disponible):</label>
      <input class="form-control mb-2" type="number" name="num_stock" min="1" required>

      <label>Precio:</label>
      <input class="form-control mb-2" type="number" name="num_precio" min="0" step="0.01" required>

      <label>Foto (jpg o png):</label>
      <input class="form-control mb-3" type="file" name="foto" accept=".jpg,.jpeg,.png">

      <button class="btn boton-rosa w-100" type="submit">Publicar producto</button>
    </form>
  </div>

  <div class="text-center">
    <a href="tienda.php" class="btn boton-rosa">Ver la tienda</a>
    <a href="mis_productos.php" class="btn btn-outline-secondary">Mis productos</a>
    <a href="php/logout.php" class="btn btn-outline-danger">Cerrar sesión</a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
