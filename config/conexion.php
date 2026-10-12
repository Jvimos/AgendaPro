<?php

$host = "localhost";
$db = "agenda_contactos";
$usuario = "root";
$password = "";
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