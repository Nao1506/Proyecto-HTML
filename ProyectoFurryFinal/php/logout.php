<?php
session_start();
session_unset();   // Limpia variables
session_destroy(); // Destruye la sesión por completo

// Sesion nueva (vacia) solo para poder dejar el aviso
session_start();
$_SESSION['flash'] = ['texto' => "Cerraste sesión. ¡Hasta pronto! 🐾", 'tipo' => "info"];

header("Location: ../index.php");
exit();
