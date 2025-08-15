<?php
// Conexión a la base de datos
$host = "localhost";
$user = "root"; // Usuario de MySQL/XAMPP
$pass = "";     // Contraseña si tienes, si no déjalo vacío
$db = "ks_beauty_studio";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';
$serviciosSeleccionados = $_POST['Servicios'] ?? [];

// Validar campos obligatorios
if (empty($nombre) || empty($correo) || empty($fecha) || empty($hora) || empty($serviciosSeleccionados)) {
    die("Todos los campos son obligatorios. <a href='index.html'>Volver</a>");
}

// 1️⃣ Revisar si el cliente existe, si no crearlo
$sqlCliente = "SELECT id_cliente FROM clientes WHERE email = ?";
$stmt = $conn->prepare($sqlCliente);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $id_cliente = $result->fetch_assoc()['id_cliente'];
} else {
    $stmtInsert = $conn->prepare("INSERT INTO clientes (nombre, email) VALUES (?, ?)");
    $stmtInsert->bind_param("ss", $nombre, $correo);
    $stmtInsert->execute();
    $id_cliente = $stmtInsert->insert_id;
    $stmtInsert->close();
}

// 2️⃣ Insertar la cita (campo servicio lo dejamos vacío, se llena en servicios_cita)
$stmtCita = $conn->prepare("INSERT INTO citas (id_cliente, servicio, fecha, hora) VALUES (?, '', ?, ?)");
$stmtCita->bind_param("iss", $id_cliente, $fecha, $hora);
$stmtCita->execute();
$id_cita = $stmtCita->insert_id;
$stmtCita->close();

// 3️⃣ Insertar cada servicio seleccionado
$stmtServicio = $conn->prepare("INSERT INTO servicios_cita (id_cita, id_servicio) VALUES (?, ?)");
foreach ($serviciosSeleccionados as $nombreServicio) {
    // Buscar ID del servicio por nombre
    $stmtBuscar = $conn->prepare("SELECT id_servicio FROM servicios WHERE nombre = ?");
    $stmtBuscar->bind_param("s", $nombreServicio);
    $stmtBuscar->execute();
    $resServ = $stmtBuscar->get_result();
    if ($resServ->num_rows > 0) {
        $id_servicio = $resServ->fetch_assoc()['id_servicio'];
        $stmtServicio->bind_param("ii", $id_cita, $id_servicio);
        $stmtServicio->execute();
    }
    $stmtBuscar->close();
}
$stmtServicio->close();

// 4️⃣ Confirmar
echo "<h2>Cita agendada con éxito</h2>";
echo "<p>Cliente: $nombre</p>";
echo "<p>Fecha: $fecha</p>";
echo "<p>Hora: $hora</p>";
echo "<p><a href='index.html'>Volver al inicio</a></p>";

$conn->close();
?>
