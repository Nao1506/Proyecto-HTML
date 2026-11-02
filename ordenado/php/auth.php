<?php
session_start(); // Arrancamos la sesión
// Los archivos dentro de /php definen $ruta_login = "../login.html" antes de incluir este archivo
if (!isset($ruta_login)) { $ruta_login = "login.html"; }
// Si no hay sesión iniciada, lo expulsamos al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . $ruta_login);
    exit();
}
?>
