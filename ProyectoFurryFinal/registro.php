<?php
require_once __DIR__ . '/php/sesion.php';

if ($logueado) {
    header("Location: index.php");
    exit();
}

$titulo = "Crear cuenta";
include __DIR__ . '/php/cabecera.php';
?>

  <div class="card producto p-4" style="max-width: 400px; margin: 40px auto;">
    <h2 class="text-center">🐾 Crear cuenta</h2>
    <form action="php/logica_registro.php" method="POST">
      <label>Nombre:</label>
      <input class="form-control mb-3" type="text" name="txt_nombre" maxlength="100" required>

      <label>Correo:</label>
      <input class="form-control mb-3" type="email" name="txt_correo" maxlength="150" required>

      <label>Contraseña:</label>
      <input class="form-control mb-3" type="password" name="txt_pass" required>

      <button class="btn boton-rosa w-100" type="submit">Registrarme</button>
    </form>
    <p class="text-center mt-3 mb-0">¿Ya tenés cuenta? <a href="login.php">Iniciá sesión</a></p>
  </div>

<?php include __DIR__ . '/php/pie.php'; ?>
