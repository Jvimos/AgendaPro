<?php
/**
 * Procesamiento y almacenamiento de un nuevo contacto
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

$nombre = trim($_POST["nombre"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$ciudad = trim($_POST["ciudad"] ?? "");
$id_categoria = $_POST["id_categoria"] ?? "";
$es_favorito = (isset($_POST["es_favorito"]) && $_POST["es_favorito"] === "1") ? 1 : 0;

// Validar campos obligatorios
if ($nombre === "" || $telefono === "" || $correo === "" || $ciudad === "" || $id_categoria === "") {
    guardarMensaje("error", "Todos los campos marcados con asterisco (*) son obligatorios.");
    header("Location: crear.php");
    exit;
}

// Validar longitudes
if (mb_strlen($nombre) > 150 || mb_strlen($telefono) > 30 || mb_strlen($correo) > 150 || mb_strlen($ciudad) > 100) {
    guardarMensaje("error", "Uno o más campos superan la longitud máxima permitida.");
    header("Location: crear.php");
    exit;
}

// Validar correo electrónico
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    guardarMensaje("error", "Ingresa un correo electrónico válido.");
    header("Location: crear.php");
    exit;
}

// Validar categoría
if (!filter_var($id_categoria, FILTER_VALIDATE_INT) || (int)$id_categoria < 1) {
    guardarMensaje("error", "La categoría seleccionada no es válida.");
    header("Location: crear.php");
    exit;
}

try {
    // Verificar que la categoría exista en la BD
    $stmtCat = $conexion->prepare("SELECT id FROM categorias WHERE id = ?");
    $stmtCat->execute([(int)$id_categoria]);
    if (!$stmtCat->fetch()) {
        guardarMensaje("error", "La categoría seleccionada no existe.");
        header("Location: crear.php");
        exit;
    }

    $sql = "INSERT INTO contactos (id_usuario, id_categoria, nombre, telefono, correo, ciudad, es_favorito)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        $_SESSION["usuario_id"],
        (int)$id_categoria,
        $nombre,
        $telefono,
        $correo,
        $ciudad,
        $es_favorito
    ]);

    guardarMensaje("exito", "Contacto '" . htmlspecialchars($nombre, ENT_QUOTES, "UTF-8") . "' registrado correctamente.");
    header("Location: listar.php");
    exit;

} catch (PDOException $e) {
    error_log("Error al guardar contacto: " . $e->getMessage());
    guardarMensaje("error", "No fue posible guardar el contacto. Inténtalo nuevamente.");
    header("Location: crear.php");
    exit;
}
