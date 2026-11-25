<?php
// Guardian: incluilo en cualquier pagina que exija haber iniciado sesion.
require_once __DIR__ . '/sesion.php';

if (!$logueado) {
    mensaje("Primero tenés que iniciar sesión.", "warning");
    header("Location: " . $base . "login.php");
    exit();
}
