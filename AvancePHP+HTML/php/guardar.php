<?php
$ruta_login = "../login.html";
include 'auth.php'; // Verificamos seguridad también aquí
include 'conexion.php';

$nombre    = mysqli_real_escape_string($conexion, $_POST['txt_nombre']);
$desc      = mysqli_real_escape_string($conexion, $_POST['txt_desc']);
$categoria = (int) $_POST['sel_categoria'];
$stock     = (int) $_POST['num_stock'];
$precio    = (float) $_POST['num_precio'];
$usuario   = $_SESSION['usuario_id'];

// Subida de la foto (queda guardada en la carpeta Img)
$imagen = "";
if ($_FILES['foto']['error'] == 0) {
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    if ($ext == "jpg" || $ext == "jpeg" || $ext == "png") {
        $imagen = time() . "_" . $usuario . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "../Img/" . $imagen);
    }
}

$sql = "CALL sp_insertar_producto($categoria, '$nombre', '$desc', $stock, $precio, '$imagen')";
mysqli_query($conexion, $sql);

// Vuelve al panel principal
header("Location: ../principal.php");
exit();
?>
