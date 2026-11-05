<?php
$ruta_login = "../login.php?aviso=1";
include_once __DIR__ . '/auth.php'; // Verificamos seguridad también aquí
include_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../principal.php");
    exit();
}

$nombre    = trim($_POST['txt_nombre'] ?? '');
$desc      = trim($_POST['txt_desc'] ?? '');
$categoria = (int) ($_POST['sel_categoria'] ?? 1);
$stock     = (int) ($_POST['num_stock'] ?? 0);
$precio    = (float) ($_POST['num_precio'] ?? 0);
$usuario   = $_SESSION['usuario_id'];

if ($nombre == '' || $stock < 1 || $precio < 0) {
    header("Location: ../principal.php?error=datos");
    exit();
}

// Subida de la foto (queda guardada en la carpeta Img)
$imagen = "";
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $es_imagen = @getimagesize($_FILES['foto']['tmp_name']) !== false; // que sea una imagen de verdad
    if ($es_imagen && ($ext == "jpg" || $ext == "jpeg" || $ext == "png")) {
        $imagen = time() . "_" . $usuario . "_" . bin2hex(random_bytes(3)) . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . "/../Img/" . $imagen);
    }
}

llamar($conexion, "CALL sp_insertar_producto($categoria, " . esc($conexion, $nombre) . ", " . esc($conexion, $desc) . ", $stock, $precio, " . esc($conexion, $imagen) . ")");

header("Location: ../principal.php?ok=1");
exit();
?>
