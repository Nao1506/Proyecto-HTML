<?php
// Este archivo se incluye al principio de TODAS las paginas.
// Arranca la sesion y deja listas estas variables:
//   $conexion  -> conexion a la base
//   $logueado  -> true si hay un usuario con la sesion iniciada
//   $es_admin  -> true si ese usuario es administrador
//   $base      -> prefijo para las rutas ("" en las paginas de la raiz, "../" dentro de /php)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/conexion.php';

$base = (realpath(dirname($_SERVER['SCRIPT_FILENAME'])) === realpath(__DIR__)) ? "../" : "";

$logueado = false;
$es_admin = false;

if (isset($_SESSION['usuario_id'])) {
    // Volvemos a mirar la base: si el usuario fue eliminado, o dejo de ser admin, se nota enseguida
    $fila = consulta($conexion, "SELECT Nombre, es_admin FROM Usuarios WHERE id_usuario = ?", "i", [$_SESSION['usuario_id']]);
    if ($fila) {
        $logueado = true;
        $es_admin = ((int) $fila[0]['es_admin'] === 1);
        $_SESSION['nombre_usuario'] = $fila[0]['Nombre'];
    } else {
        unset($_SESSION['usuario_id'], $_SESSION['nombre_usuario']);
    }
}

// Escapa texto antes de mostrarlo en pantalla (evita que se cuele HTML)
function e($texto) {
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

// Deja un aviso para mostrarlo en la proxima pagina. $tipo: success, danger, warning, info
function mensaje($texto, $tipo = "success") {
    $_SESSION['flash'] = ['texto' => $texto, 'tipo' => $tipo];
}

// Ruta de la foto de un producto (las fotos subidas viven en la carpeta Img)
function ruta_imagen($url, $base = "") {
    if (preg_match('#^https?://#i', $url)) {
        return $url;
    }
    return $base . "Img/" . rawurlencode($url);
}
