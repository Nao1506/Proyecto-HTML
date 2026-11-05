<?php
// Solo LÓGICA: deja la lista de productos en $productos.
// La página que lo incluye (tienda.php) es la que dibuja el HTML.
include_once __DIR__ . '/sesion.php';
include_once __DIR__ . '/conexion.php';

$productos = llamar($conexion, "CALL sp_listar_productos()");
?>
