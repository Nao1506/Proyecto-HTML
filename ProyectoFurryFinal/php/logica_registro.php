<?php
require_once __DIR__ . '/sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../registro.php");
    exit();
}

$nombre = trim($_POST['txt_nombre'] ?? '');
$correo = trim($_POST['txt_correo'] ?? '');
$pass   = $_POST['txt_pass'] ?? '';

if ($nombre === '' || $pass === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    mensaje("Completá todos los datos con un correo válido.", "danger");
    header("Location: ../registro.php");
    exit();
}

$res = consulta($conexion, "CALL sp_registrar_usuario(?, ?, ?)", "sss", [$nombre, $correo, $pass]);

if ($res && (int) $res[0]['creado'] === 1) {
    mensaje("Cuenta creada. Ya podés iniciar sesión 💗");
    header("Location: ../login.php");
} else {
    mensaje("Ese correo ya está registrado.", "danger");
    header("Location: ../registro.php");
}
exit();
