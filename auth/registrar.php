
<?php

session_start();

require_once "../config/conexion.php";
require_once "../includes/mensajes.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: registro.php");
    exit;
}

$nombre = trim($_POST["nombre"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$password = $_POST["password"] ?? "";
$password_confirmar = $_POST["password_confirmar"] ?? "";

if (
    $nombre === "" ||
    $correo === "" ||
    $password === "" ||
    $password_confirmar === ""
) {
    guardarMensaje("error", "Todos los campos son obligatorios.");
    header("Location: registro.php");
    exit;
}

if (strlen($nombre) > 100) {
    guardarMensaje("error", "El nombre no puede superar los 100 caracteres.");
    header("Location: registro.php");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($correo) > 150) {
    guardarMensaje("error", "Ingresa un correo electrónico válido.");
    header("Location: registro.php");
    exit;
}

if (strlen($password) < 6) {
    guardarMensaje("error", "La contraseña debe tener al menos 6 caracteres.");
    header("Location: registro.php");
    exit;
}

if ($password !== $password_confirmar) {
    guardarMensaje("error", "Las contraseñas no coinciden.");
    header("Location: registro.php");
    exit;
}

$sql = "SELECT id FROM usuarios WHERE correo = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$correo]);

if ($stmt->fetch()) {
    guardarMensaje("error", "Ya existe una cuenta con ese correo.");
    header("Location: registro.php");
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $sql = "INSERT INTO usuarios (nombre, correo, password)
            VALUES (?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        $nombre,
        $correo,
        $password_hash
    ]);

    guardarMensaje(
        "exito",
        "Cuenta creada correctamente. Ya puedes iniciar sesión."
    );

    header("Location: login.php");
    exit;

} catch (PDOException $e) {
    guardarMensaje(
        "error",
        "No fue posible completar el registro. Inténtalo nuevamente."
    );

    header("Location: registro.php");
    exit;
}
