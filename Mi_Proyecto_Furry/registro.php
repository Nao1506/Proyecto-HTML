<?php
include 'php/sesion.php';
if ($logueado) { header("Location: index.php"); exit(); }

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear cuenta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
  <div class="card producto p-4" style="max-width: 400px; margin: 60px auto;">
    <h2 class="text-center">🐾 Crear cuenta</h2>

    <?php if ($error == "existe") { ?>
      <div class="alert alert-warning">Ese correo ya está registrado. <a href="login.php">Iniciá sesión</a>.</div>
    <?php } elseif ($error == "datos") { ?>
      <div class="alert alert-danger">Completá todos los campos (la contraseña, mínimo 4 caracteres).</div>
    <?php } elseif ($error == "sistema") { ?>
      <div class="alert alert-danger">Error del sistema: ¿ejecutaste <b>BD_Furry_procedimientos.sql</b> en phpMyAdmin?</div>
    <?php } ?>

    <form action="php/logica_registro.php" method="POST">
      <label>Nombre:</label>
      <input class="form-control mb-3" type="text" name="txt_nombre" maxlength="100" required>

      <label>Correo:</label>
      <input class="form-control mb-3" type="email" name="txt_correo" maxlength="150" required>

      <label>Contraseña:</label>
      <input class="form-control mb-3" type="password" name="txt_pass" minlength="4" required>

      <button class="btn boton-rosa w-100" type="submit">Registrarme</button>
    </form>
    <p class="text-center mt-3 mb-0">¿Ya tenés cuenta? <a href="login.php">Iniciá sesión</a></p>
  </div>
</body>
</html>
