<?php

// Plantilla de configuraciÃ³n de conexiÃ³n a la base de datos para despliegue / evaluaciÃ³n.
// Para utilizar: renombrar o copiar este archivo como 'conexion.php' y configurar las credenciales locales.

$host = "localhost";
$db = "agenda_contactos";
$usuario = "root";
$password = ""; // En instalaciones estÃ¡ndar de XAMPP la contraseÃ±a de root suele estar vacÃ­a.
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$opciones = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

try {

    $conexion = new PDO($dsn, $usuario, $password, $opciones);

} catch (PDOException $e) {

    die("No fue posible conectar con la base de datos.");

}
