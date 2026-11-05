<?php
include 'php/sesion.php';
// Si ya está logueado no tiene sentido mostrar el login
if ($logueado) { header("Location: index.php"); exit(); }

$error      = $_GET['error'] ?? '';
$aviso      = isset($_GET['aviso']);
$registrado = isset($_GET['registrado']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acceso al Sistema</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
  <div class="card producto p-4" style="max-width: 400px; margin: 60px auto;">
    <h2 class="text-center">🐾 Iniciar Sesión</h2>

    <?php if ($registrado) { ?>
      <div class="alert alert-success">¡Cuenta creada! Ya podés iniciar sesión.</div>
    <?php } ?>
    <?php if ($aviso) { ?>
      <div class="alert alert-info">Iniciá sesión para continuar.</div>
    <?php } ?>
    <?php if ($error == "1") { ?>
      <div class="alert alert-danger">Correo o contraseña incorrectos.</div>
    <?php } elseif ($error == "sistema") { ?>
      <div class="alert alert-danger">Error del sistema: ¿ejecutaste <b>BD_Furry_procedimientos.sql</b> en phpMyAdmin?</div>
    <?php } ?>

    <form action="php/logica_login.php" method="POST">
      <label>Correo:</label>
      <input class="form-control mb-3" type="email" name="user" required>

      <label>Contraseña:</label>
      <input class="form-control mb-3" type="password" name="pass" required>

      <button class="btn boton-rosa w-100" type="submit">Ingresar</button>
    </form>
    <p class="text-center mt-3 mb-0">¿No tenés cuenta? <a href="registro.php">Registrate</a></p>
    <p class="text-center mb-0"><a href="index.php">Volver al inicio</a></p>
  </div>
</body>
</html>
