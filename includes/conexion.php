<?php
$host = 'localhost';
$usuario = 'root';
$clave = ''; // deja vacío si no tienes contraseña
$baseDeDatos = 'ks_citas';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$baseDeDatos", $usuario, $clave);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
}
?>