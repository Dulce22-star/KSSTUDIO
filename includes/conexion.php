<?php
$host = "localhost";
$user = "root"; // Usuario de XAMPP
$pass = "";     // Contraseña de MySQL en tu servidor local
$db = "ks_beauty_studio"; // Nombre de tu BD

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
