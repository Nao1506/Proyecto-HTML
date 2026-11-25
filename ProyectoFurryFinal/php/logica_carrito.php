<?php
require_once __DIR__ . '/auth.php'; // Para usar el carrito hay que haber iniciado sesión

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../carrito.php");
    exit();
}

$usuario = (int) $_SESSION['usuario_id'];
$accion  = $_POST['accion'] ?? '';

if ($accion === 'agregar') {
    $id       = (int) ($_POST['id_producto'] ?? 0);
    $cantidad = (int) ($_POST['num_cantidad'] ?? 0);

    $res = [];
    if ($id > 0 && $cantidad > 0) {
        $res = consulta($conexion, "CALL sp_agregar_carrito(?, ?, ?)", "iii", [$usuario, $id, $cantidad]);
    }
    if ($res && (int) $res[0]['agregado'] === 1) {
        mensaje("Producto agregado al carrito 🛒");
    } else {
        mensaje("No se pudo agregar: revisá la cantidad o el stock.", "danger");
    }
    header("Location: ../tienda.php");
    exit();
}

if ($accion === 'quitar') {
    $id = (int) ($_POST['id_producto'] ?? 0);
    consulta($conexion, "CALL sp_quitar_del_carrito(?, ?)", "ii", [$usuario, $id]);
    mensaje("Producto quitado del carrito.", "info");
    header("Location: ../carrito.php");
    exit();
}

if ($accion === 'comprar') {
    $res = consulta($conexion, "CALL sp_finalizar_compra(?)", "i", [$usuario]);
    $pedido = $res ? (int) $res[0]['id_pedido'] : 0;
    if ($pedido > 0) {
        mensaje("¡Compra realizada! Tu pedido es el n.º " . $pedido . " 💗");
        header("Location: ../tienda.php");
    } else {
        mensaje("No se pudo finalizar la compra: el carrito está vacío o falta stock.", "danger");
        header("Location: ../carrito.php");
    }
    exit();
}

header("Location: ../carrito.php");
exit();
