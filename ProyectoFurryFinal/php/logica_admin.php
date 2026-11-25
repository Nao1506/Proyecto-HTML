<?php
require_once __DIR__ . '/auth_admin.php'; // Solo administradores

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin.php");
    exit();
}

$accion = $_POST['accion'] ?? '';

if ($accion === 'eliminar_usuario') {
    $id = (int) ($_POST['id_usuario'] ?? 0);

    if ($id === (int) $_SESSION['usuario_id']) {
        mensaje("No podés eliminar tu propio usuario.", "danger");
    } else {
        $res = consulta($conexion, "CALL sp_eliminar_usuario(?)", "i", [$id]);
        if ($res && (int) $res[0]['borrado'] === 1) {
            mensaje("Usuario eliminado.");
        } else {
            mensaje("No se pudo eliminar (no existe o es administrador).", "danger");
        }
    }
    header("Location: ../admin.php");
    exit();
}

if ($accion === 'borrar_producto') {
    $id = (int) ($_POST['id_producto'] ?? 0);
    $res = consulta($conexion, "CALL sp_borrar_producto(?)", "i", [$id]);
    if ($res && (int) $res[0]['borrado'] === 1) {
        mensaje("Producto borrado de la tienda (stock en 0).");
    } else {
        mensaje("Ese producto no existe.", "danger");
    }
    header("Location: ../tienda.php");
    exit();
}

header("Location: ../admin.php");
exit();
