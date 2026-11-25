<?php
require_once __DIR__ . '/php/sesion.php';

$productos  = consulta($conexion, "CALL sp_listar_productos()");
$categorias = consulta($conexion, "SELECT id_categoria, nombre FROM Categorias ORDER BY id_categoria");

$titulo = "Tienda";
include __DIR__ . '/php/cabecera.php';
?>

  <header class="portada text-center">
    <div class="container">
      <h1>Tienda</h1>
      <p>Productos publicados por la comunidad.</p>

      <?php if ($logueado) { ?>
        <!-- Este botón abre/cierra el formulario (lo hace Bootstrap, sin JavaScript propio) -->
        <button class="btn boton-rosa" type="button" data-bs-toggle="collapse" data-bs-target="#form-producto">
          + Publicar un producto
        </button>
      <?php } else { ?>
        <a href="login.php" class="btn boton-rosa">Iniciá sesión para publicar y comprar</a>
      <?php } ?>
    </div>
  </header>

  <main class="container py-5">

    <?php if ($logueado) { ?>
    <!-- Formulario para subir un producto -->
    <div class="collapse mb-5" id="form-producto">
      <div class="card producto p-4" style="max-width: 450px; margin: 0 auto;">
        <h3>Subir un producto</h3>
        <!-- enctype es obligatorio para poder enviar archivos -->
        <form action="php/guardar.php" method="POST" enctype="multipart/form-data">
          <label>Nombre:</label>
          <input class="form-control mb-2" type="text" name="txt_nombre" maxlength="150" required>

          <label>Descripción:</label>
          <input class="form-control mb-2" type="text" name="txt_desc" maxlength="100" required>

          <label>Categoría:</label>
          <select class="form-select mb-2" name="sel_categoria">
            <?php foreach ($categorias as $c) { ?>
              <option value="<?php echo (int) $c['id_categoria']; ?>"><?php echo e($c['nombre']); ?></option>
            <?php } ?>
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
    </div>
    <?php } ?>

    <?php if (count($productos) == 0) { ?>
      <p class="text-center">Todavía no hay productos a la venta.</p>
    <?php } ?>

    <div class="row g-4">
      <?php foreach ($productos as $p) { ?>
      <div class="col-md-4">
        <div class="card producto h-100">

          <?php if ($p['imagen'] != "") { ?>
            <img src="<?php echo e(ruta_imagen($p['imagen'])); ?>" alt="<?php echo e($p['nombre']); ?>">
          <?php } else { ?>
            <div class="imagen-producto">🐾</div>
          <?php } ?>

          <div class="card-body">
            <h5><?php echo e($p['nombre']); ?></h5>
            <p><?php echo e($p['descripcion']); ?></p>
            <p class="precio">$<?php echo number_format($p['Precio_actual'], 0, ',', '.'); ?></p>
            <p class="mb-1"><small>Categoría: <?php echo e($p['categoria']); ?></small></p>
            <p><strong>Stock: <?php echo (int) $p['stock_general']; ?></strong></p>

            <?php if ($logueado) { ?>
              <!-- Agregar al carrito -->
              <form action="php/logica_carrito.php" method="POST">
                <input type="hidden" name="accion" value="agregar">
                <input type="hidden" name="id_producto" value="<?php echo (int) $p['id_producto']; ?>">
                <input class="form-control mb-2" type="number" name="num_cantidad" value="1" min="1" max="<?php echo (int) $p['stock_general']; ?>">
                <button class="btn boton-rosa w-100" type="submit">🛒 Agregar al carrito</button>
              </form>
            <?php } else { ?>
              <a href="login.php" class="btn boton-rosa w-100">Ingresá para comprar</a>
            <?php } ?>

            <?php if ($es_admin) { ?>
              <!-- Solo administradores: borrar producto (le pone el stock en 0) -->
              <button class="btn btn-outline-danger btn-sm w-100 mt-2" type="button"
                      data-bs-toggle="collapse" data-bs-target="#borrar-<?php echo (int) $p['id_producto']; ?>">
                Borrar producto
              </button>
              <div class="collapse mt-2" id="borrar-<?php echo (int) $p['id_producto']; ?>">
                <form action="php/logica_admin.php" method="POST">
                  <input type="hidden" name="accion" value="borrar_producto">
                  <input type="hidden" name="id_producto" value="<?php echo (int) $p['id_producto']; ?>">
                  <small class="d-block mb-1">¿Seguro? Se deja el stock en 0 y sale de la tienda.</small>
                  <button class="btn btn-danger btn-sm w-100" type="submit">Sí, borrar</button>
                </form>
              </div>
            <?php } ?>
          </div>

        </div>
      </div>
      <?php } ?>
    </div>
  </main>

<?php include __DIR__ . '/php/pie.php'; ?>
