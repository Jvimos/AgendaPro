<?php
/**
 * Configuración de conexión a la base de datos para despliegue / evaluación.
 * Para utilizar: renombrar o copiar este archivo como 'conexion.php' y configurar las credenciales locales de su respectivo equipo o hosting.
 */

// Credenciales para Servidor Local (XAMPP):
$host = "localhost";
$db = "agenda_contactos";
$usuario = "root";
$password = ""; 
$charset = "utf8mb4";

// Credenciales para Hosting Gratuito (InfinityFree - vimsjordi.free.nf):
// $host = "sql213.infinityfree.com";
// $db = "if0_43468314_agenda_contactos";
// $usuario = "if0_43468314";
// $password = "su_contraseña_de_infinityfree";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$opciones = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
];

try {
    $conexion = new PDO($dsn, $usuario, $password, $opciones);
} catch (PDOException $e) {
    die("No fue posible conectar con la base de datos.");
}
