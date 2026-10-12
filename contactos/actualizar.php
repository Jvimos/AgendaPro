
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

$id = $_POST["id"] ?? "";
$nombre = trim($_POST["nombre"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$ciudad = trim($_POST["ciudad"] ?? "");

if (!filter_var($id, FILTER_VALIDATE_INT) || (int)$id < 1) {
    guardarMensaje("error", "El contacto seleccionado no es válido.");
    header("Location: listar.php");
    exit;
}

if (
    $nombre === "" ||
    $telefono === "" ||
    $correo === "" ||
    $ciudad === ""
) {
    guardarMensaje("error", "Todos los campos son obligatorios.");
    header("Location: editar.php?id=" . (int)$id);
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
    header("Location: editar.php?id=" . (int)$id);
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    guardarMensaje("error", "Ingresa un correo electrónico válido.");
    header("Location: editar.php?id=" . (int)$id);
    exit;
}

try {
    $sql = "UPDATE contactos
            SET nombre = ?, telefono = ?, correo = ?, ciudad = ?
            WHERE id = ? AND id_usuario = ?";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        $nombre,
        $telefono,
        $correo,
        $ciudad,
        (int)$id,
        $_SESSION["usuario_id"]
    ]);

    if ($stmt->rowCount() === 0) {
        $sql = "SELECT id FROM contactos
                WHERE id = ? AND id_usuario = ?";

        $verificar = $conexion->prepare($sql);
        $verificar->execute([
            (int)$id,
            $_SESSION["usuario_id"]
        ]);

        if (!$verificar->fetch()) {
            guardarMensaje(
                "error",
                "El contacto no existe o no tienes permiso para modificarlo."
            );

            header("Location: listar.php");
            exit;
        }
    }

    guardarMensaje("exito", "Contacto actualizado correctamente.");
    header("Location: listar.php");
    exit;

} catch (PDOException $e) {
    guardarMensaje(
        "error",
        "No fue posible actualizar el contacto. Inténtalo nuevamente."
    );

    header("Location: editar.php?id=" . (int)$id);
    exit;
}