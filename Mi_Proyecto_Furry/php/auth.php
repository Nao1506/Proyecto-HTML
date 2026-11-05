<?php
// Guardián: si no hay sesión iniciada, manda al login.
// Las páginas dentro de /php definen $ruta_login = "../login.php?aviso=1" antes de incluir este archivo.
include_once __DIR__ . '/sesion.php';
if (!isset($ruta_login)) { $ruta_login = "login.php?aviso=1"; }
if (!$logueado) {
    header("Location: " . $ruta_login);
    exit();
}
?>
