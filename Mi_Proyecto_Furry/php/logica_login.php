<?php
include_once __DIR__ . '/sesion.php';
include_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit();
}

$correo = trim($_POST['user'] ?? '');
$pass   = $_POST['pass'] ?? '';

try {
    // El SP devuelve el usuario por correo; la clave la verificamos acá en PHP
    $filas = llamar($conexion, "CALL sp_obtener_usuario(" . esc($conexion, $correo) . ")");
} catch (mysqli_sql_exception $e) {
    // Casi seguro: falta ejecutar BD_Furry_procedimientos.sql
    header("Location: ../login.php?error=sistema");
    exit();
}

$usuario = $filas[0] ?? null;
$valido  = false;

if ($usuario) {
    $guardada = $usuario['clave'];

    if (password_verify($pass, $guardada)) {
        // Clave guardada con hash (usuarios nuevos)
        $valido = true;
    } elseif (hash_equals($guardada, $pass)) {
        // Clave vieja guardada en texto plano: entra y la convertimos a hash
        $valido = true;
        $nuevo_hash = password_hash($pass, PASSWORD_DEFAULT);
        llamar($conexion, "CALL sp_actualizar_clave(" . (int) $usuario['id_usuario'] . ", " . esc($conexion, $nuevo_hash) . ")");
    }
}

if ($valido) {
    session_regenerate_id(true); // evita robo de sesión
    $_SESSION['usuario_id']     = (int) $usuario['id_usuario'];
    $_SESSION['nombre_usuario'] = $usuario['Nombre'];
    header("Location: ../index.php"); // Vuelve al inicio, ya logueado
} else {
    header("Location: ../login.php?error=1");
}
exit();
?>
