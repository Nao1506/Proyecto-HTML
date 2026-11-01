<?php
// Esta página es pública: cualquiera puede ver los productos
include 'conexion.php';

// 1. Ejecutamos SP
$sql = "CALL sp_listar_productos()";
$resultado = mysqli_query($conexion, $sql);

// 2. Guardamos todos los productos en una lista para la vista
$productos = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $productos[] = $fila;
}

// 3. ¡CARGAMOS EL HTML!
include '../vista_catalogo.php';
?>
