<?php
include 'conexion.php';

$nombre = mysqli_real_escape_string($conexion, $_POST['txt_nombre']);
$correo = mysqli_real_escape_string($conexion, $_POST['txt_correo']);
$pass   = mysqli_real_escape_string($conexion, $_POST['txt_pass']);

$sql = "CALL sp_registrar_usuario('$nombre', '$correo', '$pass')";
mysqli_query($conexion, $sql);

// Ya puede iniciar sesión
header("Location: ../login.html");
exit();
?>
