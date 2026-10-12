 
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

if (!filter_var($id, FILTER_VALIDATE_INT) || (int)$id < 1) {
    guardarMensaje("error", "El contacto seleccionado no es válido.");
    header("Location: listar.php");
    exit;
}

try {
    $sql = "DELETE FROM contactos
            WHERE id = ? AND id_usuario = ?";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        (int)$id,
        $_SESSION["usuario_id"]
    ]);

    if ($stmt->rowCount() === 0) {
        guardarMensaje(
            "error",
            "El contacto no existe o no tienes permiso para eliminarlo."
        );
    } else {
        guardarMensaje("exito", "Contacto eliminado correctamente.");
    }

    header("Location: listar.php");
    exit;

} catch (PDOException $e) {
    guardarMensaje(
        "error",
        "No fue posible eliminar el contacto. Inténtalo nuevamente."
    );

    header("Location: listar.php");
    exit;
}