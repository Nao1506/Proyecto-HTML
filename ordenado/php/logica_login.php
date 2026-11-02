<?php
session_start();
include 'conexion.php';

$u = mysqli_real_escape_string($conexion, $_POST['user']);
$p = mysqli_real_escape_string($conexion, $_POST['pass']);

$sql = "CALL sp_validar_usuario('$u', '$p')";
$res = mysqli_query($conexion, $sql);

if ($fila = mysqli_fetch_assoc($res)) {
    // ÉXITO: Creamos las variables de sesión
    $_SESSION['usuario_id'] = $fila['id_usuario'];
    $_SESSION['nombre_usuario'] = $fila['Nombre'];
    header("Location: ../principal.php"); // Entra al panel
} else {
    // ERROR: Vuelve al login
    header("Location: ../login.html");
}
?>
