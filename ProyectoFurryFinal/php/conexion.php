<?php
// Si algo falla en la base, mysqli avisa con un error claro (en vez de seguir en silencio)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conexion = new mysqli("localhost", "root", "", "tienda_online");
$conexion->set_charset("utf8mb4");


function consulta($conexion, $sql, $tipos = "", $params = []) {
    $stmt = $conexion->prepare($sql);
    if ($tipos !== "") {
        $stmt->bind_param($tipos, ...$params);
    }
    $stmt->execute();

    $filas = [];
    $res = $stmt->get_result();
    if ($res) {
        while ($fila = $res->fetch_assoc()) {
            $filas[] = $fila;
        }
        $res->free();
    }
    $stmt->close();


    while ($conexion->more_results()) {
        $conexion->next_result();
    }
    return $filas;
}
