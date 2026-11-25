<?php
require_once __DIR__ . '/php/auth_admin.php'; // Solo administradores

$usuarios = consulta($conexion, "CALL sp_listar_usuarios()");

$titulo = "Administrar";
include __DIR__ . '/php/cabecera.php';
?>

  <header class="portada text-center">
    <div class="container">
      <h1>⚙️ Panel de administración</h1>
      <p>Usuarios registrados. Los productos se borran desde la <a href="tienda.php">tienda</a>.</p>
    </div>
  </header>

  <main class="container py-5">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Registro</th>
            <th>Tipo</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $u) { ?>
          <tr>
            <td><?php echo (int) $u['id_usuario']; ?></td>
            <td><?php echo e($u['Nombre']); ?></td>
            <td><?php echo e($u['Correo']); ?></td>
            <td><?php echo e($u['Fecha_registro']); ?></td>
            <td>
              <?php if ($u['es_admin'] == 1) { ?>
                <span class="badge bg-dark">Admin</span>
              <?php } else { ?>
                <span class="badge bg-secondary">Usuario</span>
              <?php } ?>
            </td>
            <td>
              <?php if ($u['es_admin'] != 1) { ?>
                <button class="btn btn-outline-danger btn-sm" type="button"
                        data-bs-toggle="collapse" data-bs-target="#borrar-u<?php echo (int) $u['id_usuario']; ?>">
                  Eliminar
                </button>
                <div class="collapse mt-2" id="borrar-u<?php echo (int) $u['id_usuario']; ?>">
                  <form action="php/logica_admin.php" method="POST">
                    <input type="hidden" name="accion" value="eliminar_usuario">
                    <input type="hidden" name="id_usuario" value="<?php echo (int) $u['id_usuario']; ?>">
                    <small class="d-block mb-1">¿Seguro? Se borra el usuario con sus pedidos y carrito.</small>
                    <button class="btn btn-danger btn-sm" type="submit">Sí, eliminar</button>
                  </form>
                </div>
              <?php } ?>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </main>

<?php include __DIR__ . '/php/pie.php'; ?>
