<?php
// Guardian: incluilo en las paginas que solo puede usar un administrador.
require_once __DIR__ . '/auth.php';

if (!$es_admin) {
    mensaje("No tenés permiso para entrar ahí.", "danger");
    header("Location: " . $base . "index.php");
    exit();
}
