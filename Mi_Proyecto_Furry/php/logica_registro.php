<?php
include_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../registro.php");
    exit();
}

$nombre = trim($_POST['txt_nombre'] ?? '');
$correo = trim($_POST['txt_correo'] ?? '');
$pass   = $_POST['txt_pass'] ?? '';

if ($nombre == '' || $correo == '' || strlen($pass) < 4) {
    header("Location: ../registro.php?error=datos");
    exit();
}

// Nunca se guarda la clave en texto plano
$hash = password_hash($pass, PASSWORD_DEFAULT);

try {
    $filas = llamar($conexion, "CALL sp_registrar_usuario(" . esc($conexion, $nombre) . ", " . esc($conexion, $correo) . ", " . esc($conexion, $hash) . ")");
} catch (mysqli_sql_exception $e) {
    header("Location: ../registro.php?error=sistema");
    exit();
}

if (!empty($filas) && (int) $filas[0]['creado'] === 0) {
    header("Location: ../registro.php?error=existe"); // el correo ya estaba registrado
} else {
    header("Location: ../login.php?registrado=1");
}
exit();
?>
