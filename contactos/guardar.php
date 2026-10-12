
<?php

session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once "../config/conexion.php";
require_once "../includes/mensajes.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: listar.php");
    exit;
}

$nombre = trim($_POST["nombre"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$ciudad = trim($_POST["ciudad"] ?? "");

if (
    $nombre === "" ||
    $telefono === "" ||
    $correo === "" ||
    $ciudad === ""
) {
    guardarMensaje("error", "Todos los campos son obligatorios.");
    header("Location: crear.php");
    exit;
}

if (
    mb_strlen($nombre) > 150 ||
    mb_strlen($telefono) > 30 ||
    mb_strlen($correo) > 150 ||
    mb_strlen($ciudad) > 100
) {
    guardarMensaje(
        "error",
        "Uno o más campos superan la longitud permitida."
    );
    header("Location: crear.php");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    guardarMensaje("error", "Ingresa un correo electrónico válido.");
    header("Location: crear.php");
    exit;
}

try {
    $sql = "INSERT INTO contactos
            (id_usuario, nombre, telefono, correo, ciudad)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        $_SESSION["usuario_id"],
        $nombre,
        $telefono,
        $correo,
        $ciudad
    ]);

    guardarMensaje("exito", "Contacto registrado correctamente.");
    header("Location: listar.php");
    exit;

} catch (PDOException $e) {
    guardarMensaje(
        "error",
        "No fue posible guardar el contacto. Inténtalo nuevamente."
    );

    header("Location: crear.php");
    exit;
}