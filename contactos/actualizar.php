<?php
/**
 * Procesamiento de actualización de contacto
 * Con soporte para Categoría (Opción C) y Favorito (Opción D)
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
$nombre = trim($_POST["nombre"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$ciudad = trim($_POST["ciudad"] ?? "");
$id_categoria = $_POST["id_categoria"] ?? "";
$es_favorito = (isset($_POST["es_favorito"]) && $_POST["es_favorito"] === "1") ? 1 : 0;

if (!filter_var($id, FILTER_VALIDATE_INT) || (int)$id < 1) {
    guardarMensaje("error", "El contacto seleccionado no es válido.");
    header("Location: listar.php");
    exit;
}

$id = (int)$id;

if ($nombre === "" || $telefono === "" || $correo === "" || $ciudad === "" || $id_categoria === "") {
    guardarMensaje("error", "Todos los campos obligatorios (*) deben ser completados.");
    header("Location: editar.php?id=" . $id);
    exit;
}

if (mb_strlen($nombre) > 150 || mb_strlen($telefono) > 30 || mb_strlen($correo) > 150 || mb_strlen($ciudad) > 100) {
    guardarMensaje("error", "Uno o más campos superan la longitud máxima permitida.");
    header("Location: editar.php?id=" . $id);
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    guardarMensaje("error", "Ingresa un correo electrónico válido.");
    header("Location: editar.php?id=" . $id);
    exit;
}

if (!filter_var($id_categoria, FILTER_VALIDATE_INT) || (int)$id_categoria < 1) {
    guardarMensaje("error", "La categoría seleccionada no es válida.");
    header("Location: editar.php?id=" . $id);
    exit;
}

try {
    // Validar existencia de categoría
    $stmtCat = $conexion->prepare("SELECT id FROM categorias WHERE id = ?");
    $stmtCat->execute([(int)$id_categoria]);
    if (!$stmtCat->fetch()) {
        guardarMensaje("error", "La categoría seleccionada no existe.");
        header("Location: editar.php?id=" . $id);
        exit;
    }

    $sql = "UPDATE contactos
            SET nombre = ?, telefono = ?, correo = ?, ciudad = ?, id_categoria = ?, es_favorito = ?
            WHERE id = ? AND id_usuario = ?";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        $nombre,
        $telefono,
        $correo,
        $ciudad,
        (int)$id_categoria,
        $es_favorito,
        $id,
        $_SESSION["usuario_id"]
    ]);

    if ($stmt->rowCount() === 0) {
        // Verificar si el contacto existe y pertenece al usuario
        $verificar = $conexion->prepare("SELECT id FROM contactos WHERE id = ? AND id_usuario = ?");
        $verificar->execute([$id, $_SESSION["usuario_id"]]);

        if (!$verificar->fetch()) {
            guardarMensaje("error", "El contacto no existe o no tienes permiso para modificarlo.");
            header("Location: listar.php");
            exit;
        }
    }

    guardarMensaje("exito", "Contacto '" . htmlspecialchars($nombre, ENT_QUOTES, "UTF-8") . "' actualizado correctamente.");
    header("Location: listar.php");
    exit;

} catch (PDOException $e) {
    error_log("Error al actualizar contacto: " . $e->getMessage());
    guardarMensaje("error", "No fue posible actualizar el contacto. Inténtalo nuevamente.");
    header("Location: editar.php?id=" . $id);
    exit;
}
