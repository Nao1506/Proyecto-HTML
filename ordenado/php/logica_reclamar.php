<?php
$ruta_login = "../login.html";
include 'auth.php'; // Para reclamar hay que haber iniciado sesión
include 'conexion.php';

$id       = (int) $_POST['id_producto'];
$cantidad = (int) $_POST['num_cantidad'];

if ($cantidad > 0) {
    // El SP resta del stock general (solo si alcanza)
    $sql = "CALL sp_reclamar_producto($id, $cantidad)";
    mysqli_query($conexion, $sql);
}

// Vuelve a la tienda con el stock actualizado
header("Location: logica_catalogo.php");
exit();
?>
