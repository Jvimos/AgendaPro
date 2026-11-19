<?php
/**
 * Alternar estado de favorito (Toggle Favorito)
 * Opción D: Contactos favoritos
 */

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
$retorno = $_POST["retorno"] ?? "listar.php";

// Sanitizar URL de retorno para seguridad
if (!preg_match('/^listar\.php(\?.*)?$/', $retorno)) {
    $retorno = "listar.php";
}

if (!filter_var($id, FILTER_VALIDATE_INT) || (int)$id < 1) {
    guardarMensaje("error", "Contacto no válido.");
    header("Location: " . $retorno);
    exit;
}

try {
    // Obtener estado actual
    $stmt = $conexion->prepare("SELECT es_favorito FROM contactos WHERE id = ? AND id_usuario = ?");
    $stmt->execute([(int)$id, $_SESSION["usuario_id"]]);
    $contacto = $stmt->fetch();

    if (!$contacto) {
        guardarMensaje("error", "El contacto no existe o no tienes permiso para modificarlo.");
        header("Location: " . $retorno);
        exit;
    }

    $nuevoEstado = $contacto["es_favorito"] == 1 ? 0 : 1;

    $update = $conexion->prepare("UPDATE contactos SET es_favorito = ? WHERE id = ? AND id_usuario = ?");
    $update->execute([$nuevoEstado, (int)$id, $_SESSION["usuario_id"]]);

    $texto = $nuevoEstado === 1 ? "Contacto añadido a favoritos." : "Contacto retirado de favoritos.";
    guardarMensaje("exito", $texto);

    header("Location: " . $retorno);
    exit;

} catch (PDOException $e) {
    guardarMensaje("error", "No fue posible actualizar el estado de favorito.");
    header("Location: " . $retorno);
    exit;
}
