<?php
// Que los errores de MySQL lancen excepciones (así podemos atraparlos con try/catch)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conexion(
$host = 'localhost';
$db   = 'tienda_online'; // Asegúrate de que el nombre de la base de datos sea correcto
$user = 'root';         // Cambia esto si tu usuario es diferente
$pass = '';             // Coloca la contraseña aquí si tienes una; de lo contrario, déjalo vacío
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

);


// Escapa un valor y lo devuelve entre comillas, listo para meter en un CALL
function esc($conexion, $valor) {
    return "'" . $conexion->real_escape_string((string) $valor) . "'";
}

// Ejecuta un CALL a un procedimiento almacenado y devuelve las filas del primer resultado.
// Consume todos los resultados pendientes, así NO aparece el error "Commands out of sync".
function llamar($conexion, $sql) {
    $filas = [];
    $primero = true;
    $conexion->multi_query($sql);
    do {
        if ($resultado = $conexion->store_result()) {
            if ($primero) {
                while ($fila = $resultado->fetch_assoc()) { $filas[] = $fila; }
                $primero = false;
            }
            $resultado->free();
        }
    } while ($conexion->more_results() && $conexion->next_result());
    return $filas;
}

// La foto puede ser un archivo de la carpeta Img/ o una URL completa
function url_imagen($imagen) {
    if ($imagen == "") return "";
    if (preg_match('#^https?://#', $imagen)) return $imagen;
    return "Img/" . $imagen;
}
?>
