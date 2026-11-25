<?php
require_once __DIR__ . '/auth.php'; // Solo con sesión iniciada

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../tienda.php");
    exit();
}

// Si algo está mal: deja un aviso y vuelve a la tienda
function fallar($texto) {
    mensaje($texto, "danger");
    header("Location: ../tienda.php");
    exit();
}

$nombre    = trim($_POST['txt_nombre'] ?? '');
$desc      = trim($_POST['txt_desc'] ?? '');
$categoria = (int) ($_POST['sel_categoria'] ?? 0);
$stock     = (int) ($_POST['num_stock'] ?? 0);
$precio    = (float) ($_POST['num_precio'] ?? -1);
$usuario   = (int) $_SESSION['usuario_id'];

if ($nombre === '' || $desc === '' || $stock < 1 || $precio < 0) {
    fallar("Completá todos los datos del producto (stock mínimo 1).");
}
if (!consulta($conexion, "SELECT 1 AS ok FROM Categorias WHERE id_categoria = ?", "i", [$categoria])) {
    fallar("La categoría elegida no existe.");
}
$nombre = mb_substr($nombre, 0, 150);
$desc   = mb_substr($desc, 0, 100);

// Subida de la foto (queda guardada en la carpeta Img). La foto es opcional.
$imagen = "";
if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        fallar("No se pudo subir la foto. Probá con otra.");
    }
    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $es_imagen = in_array($ext, ["jpg", "jpeg", "png"]) && getimagesize($_FILES['foto']['tmp_name']) !== false;
    if (!$es_imagen || $_FILES['foto']['size'] > 5 * 1024 * 1024) {
        fallar("La foto tiene que ser un jpg o png de hasta 5 MB.");
    }
    $imagen = time() . "_" . $usuario . "_" . bin2hex(random_bytes(3)) . "." . $ext;
    move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . "/../Img/" . $imagen);
}

consulta($conexion, "CALL sp_insertar_producto(?, ?, ?, ?, ?, ?)", "issids",
         [$categoria, $nombre, $desc, $stock, $precio, $imagen]);

mensaje("¡Producto publicado! 🐾");
header("Location: ../tienda.php");
exit();
