
<?php

session_start();

require_once "../config/conexion.php";
require_once "../includes/mensajes.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$correo = trim($_POST["correo"] ?? "");
$password = $_POST["password"] ?? "";

if ($correo === "" || $password === "") {
    guardarMensaje("error", "Ingresa tu correo y contraseña.");
    header("Location: login.php");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    guardarMensaje("error", "El correo electrónico no es válido.");
    header("Location: login.php");
    exit;
}

$sql = "SELECT id, nombre, correo, password
        FROM usuarios
        WHERE correo = ?";

$stmt = $conexion->prepare($sql);
$stmt->execute([$correo]);

$usuario = $stmt->fetch();

if (!$usuario || !password_verify($password, $usuario["password"])) {
    guardarMensaje("error", "Correo o contraseña incorrectos.");
    header("Location: login.php");
    exit;
}

session_regenerate_id(true);

$_SESSION["usuario_id"] = $usuario["id"];
$_SESSION["usuario_nombre"] = $usuario["nombre"];
$_SESSION["usuario_correo"] = $usuario["correo"];

header("Location: ../contactos/listar.php");
exit;