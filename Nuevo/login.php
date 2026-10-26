<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Método no permitido.");
}

$nombre = trim($_POST["nombre"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";
$telefono = trim($_POST["telefono"] ?? "");

if (
    $nombre === "" ||
    !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
    strlen($contrasena) < 8
) {
    exit("Verificá los datos. La contraseña debe tener al menos 8 caracteres.");
}

$hash = password_hash($contrasena, PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO Usuarios
            (Nombre, Correo, `Contraseña`, Telefono, id_rol)
            VALUES (?, ?, ?, ?, NULL)";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        $nombre,
        $correo,
        $hash,
        $telefono
    ]);

    echo "Usuario registrado correctamente.";

} catch (PDOException $e) {
    if ($e->getCode() === "23000") {
        echo "Ese correo ya está registrado.";
    } else {
        error_log($e->getMessage());
        echo "Ocurrió un error al registrar el usuario.";
    }
}
?>