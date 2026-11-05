<?php
$ruta_login = "../login.php?aviso=1";
include_once __DIR__ . '/auth.php'; // Para reclamar hay que haber iniciado sesión
include_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../tienda.php");
    exit();
}

$id       = (int) ($_POST['id_producto'] ?? 0);
$cantidad = (int) ($_POST['num_cantidad'] ?? 0);
$usuario  = (int) $_SESSION['usuario_id'];

if ($id < 1 || $cantidad < 1) {
    header("Location: ../tienda.php?error=datos");
    exit();
}

// El SP resta del stock (solo si alcanza) y deja el producto guardado en Pedidos/Detalle_pedido
$res = llamar($conexion, "CALL sp_reclamar_producto($usuario, $id, $cantidad)");

if (!empty($res) && (int) $res[0]['ok'] === 1) {
    header("Location: ../mis_productos.php?ok=1");  // ahí queda guardado lo que reclamaste
} else {
    header("Location: ../tienda.php?error=stock");  // no alcanzaba el stock
}
exit();
?>
