<?php
require_once __DIR__ . '/php/auth.php'; // Solo con sesión iniciada

$items = consulta($conexion, "CALL sp_ver_carrito(?)", "i", [$_SESSION['usuario_id']]);

$total = 0;
$hay_problema_stock = false;
foreach ($items as $it) {
    $total += $it['subtotal'];
    if ($it['cantidad'] > $it['stock_general']) { $hay_problema_stock = true; }
}

$titulo = "Mi carrito";
include __DIR__ . '/php/cabecera.php';
?>

  <header class="portada text-center">
    <div class="container">
      <h1>🛒 Mi carrito</h1>
    </div>
  </header>

  <main class="container py-5">

    <?php if (count($items) == 0) { ?>
      <p class="text-center">Tu carrito está vacío.</p>
      <p class="text-center"><a href="tienda.php" class="btn boton-rosa">Ir a la tienda</a></p>

    <?php } else { ?>

      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th></th>
              <th>Producto</th>
              <th>Precio</th>
              <th>Cantidad</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $it) { ?>
            <tr>
              <td>
                <?php if ($it['imagen'] != "") { ?>
                  <img class="miniatura" src="<?php echo e(ruta_imagen($it['imagen'])); ?>" alt="">
                <?php } else { ?>
                  <span class="miniatura sin-foto">🐾</span>
                <?php } ?>
              </td>
              <td>
                <?php echo e($it['nombre']); ?>
                <?php if ($it['cantidad'] > $it['stock_general']) { ?>
                  <br><small class="text-danger">Solo quedan <?php echo (int) $it['stock_general']; ?> en stock</small>
                <?php } ?>
              </td>
              <td>$<?php echo number_format($it['Precio_actual'], 0, ',', '.'); ?></td>
              <td><?php echo (int) $it['cantidad']; ?></td>
              <td>$<?php echo number_format($it['subtotal'], 0, ',', '.'); ?></td>
              <td>
                <form action="php/logica_carrito.php" method="POST">
                  <input type="hidden" name="accion" value="quitar">
                  <input type="hidden" name="id_producto" value="<?php echo (int) $it['id_producto']; ?>">
                  <button class="btn btn-outline-danger btn-sm" type="submit">Quitar</button>
                </form>
              </td>
            </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="4" class="text-end">Total:</th>
              <th class="precio">$<?php echo number_format($total, 0, ',', '.'); ?></th>
              <th></th>
            </tr>
          </tfoot>
        </table>
      </div>

      <div class="d-flex justify-content-between flex-wrap gap-2">
        <a href="tienda.php" class="btn btn-outline-secondary">Seguir comprando</a>

        <?php if ($hay_problema_stock) { ?>
          <span class="text-danger align-self-center">Quitá los productos sin stock suficiente para poder comprar.</span>
        <?php } else { ?>
          <form action="php/logica_carrito.php" method="POST">
            <input type="hidden" name="accion" value="comprar">
            <button class="btn boton-rosa" type="submit">Finalizar compra</button>
          </form>
        <?php } ?>
      </div>

    <?php } ?>

  </main>

<?php include __DIR__ . '/php/pie.php'; ?>
