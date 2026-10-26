<?php
require_once "conexion.php";

$sql = "SELECT id_producto, nombre, descripcion,
               Precio_actual, stock_general
        FROM Productos
        ORDER BY id_producto DESC";

$consulta = $conexion->query($sql);
$productos = $consulta->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos de la tienda</title>
</head>
<body>