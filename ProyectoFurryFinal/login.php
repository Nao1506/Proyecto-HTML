<?php
require_once __DIR__ . '/php/sesion.php';

// Si ya inició sesión no tiene sentido mostrar el login
if ($logueado) {
    header("Location: index.php");
    exit();
}

$titulo = "Ingresar";
include __DIR__ . '/php/cabecera.php';
?>

  <div class="card producto p-4" style="max-width: 400px; margin: 40px auto;">
    <h2 class="text-center">🐾 Iniciar Sesión</h2>
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

<?php include __DIR__ . '/php/pie.php'; ?>
