<?php
require_once __DIR__ . '/sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit();
}

$u = trim($_POST['user'] ?? '');
$p = $_POST['pass'] ?? '';

$filas = consulta($conexion, "CALL sp_validar_usuario(?, ?)", "ss", [$u, $p]);

if ($filas) {
    // ÉXITO: guardamos al usuario en la sesión (así sigue logueado en todas las páginas)
    session_regenerate_id(true);
    $_SESSION['usuario_id']     = (int) $filas[0]['id_usuario'];
    $_SESSION['nombre_usuario'] = $filas[0]['Nombre'];

    mensaje("¡Bienvenido/a, " . $filas[0]['Nombre'] . "! 🐾");
    header("Location: ../index.php");
} else {
    // ERROR: vuelve al login con un aviso
    mensaje("Correo o contraseña incorrectos.", "danger");
    header("Location: ../login.php");
}
exit();
