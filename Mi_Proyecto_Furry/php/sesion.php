<?php
// Arranca la sesión UNA sola vez y la deja recordada 7 días en el navegador.
// Incluí este archivo al principio de cualquier página que quiera saber si hay alguien logueado.
if (session_status() === PHP_SESSION_NONE) {
    $vida = 60 * 60 * 24 * 7; // 7 días
    ini_set('session.gc_maxlifetime', $vida);
    session_set_cookie_params([
        'lifetime' => $vida,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

$logueado      = isset($_SESSION['usuario_id']);
$nombre_sesion = $logueado ? $_SESSION['nombre_usuario'] : '';
?>
