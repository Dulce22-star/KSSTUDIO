<?php
// Incluimos la conexión a la base de datos
include 'includes/conexion.php';

// Inicializamos variable de mensaje
$mensaje = "";

// Verificamos que los datos hayan sido enviados por POST
if(isset($_POST['nombre'], $_POST['correo'], $_POST['fecha'], $_POST['hora'], $_POST['Servicios'])) {

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $servicios = implode(", ", $_POST['Servicios']); // Convertimos el array en texto separado por comas
    $estado = "Agendada"; // Estado inicial de la cita

    try {
        // Primero, verificamos si la clienta ya existe
        $stmt = $conexion->prepare("SELECT id_cliente FROM clientes WHERE email = :email");
        $stmt->bindParam(':email', $correo);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            // Cliente ya existe
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_cliente = $cliente['id_cliente'];
        } else {
            // Insertamos nueva clienta
            $stmt = $conexion->prepare("INSERT INTO clientes (nombre, email) VALUES (:nombre, :email)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':email', $correo);
            $stmt->execute();
            $id_cliente = $conexion->lastInsertId();
        }

        // Insertamos la cita
        $stmt = $conexion->prepare("INSERT INTO citas (id_cliente, fecha, hora, servicios, estado) VALUES (:id_cliente, :fecha, :hora, :servicios, :estado)");
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':hora', $hora);
        $stmt->bindParam(':servicios', $servicios);
        $stmt->bindParam(':estado', $estado);
        $stmt->execute();

        // Mensaje de éxito
        $mensaje = "<p style='color:green; text-align:center;'>¡Tu cita ha sido agendada correctamente!</p>";

    } catch(PDOException $e){
        // Mensaje de error
        $mensaje = "<p style='color:red; text-align:center;'>Error al guardar la cita: " . $e->getMessage() . "</p>";
    }

} else {
    $mensaje = "<p style='color:red; text-align:center;'>Todos los campos son requeridos.</p>";
}

// Mostramos el mensaje y redirigimos al formulario después de 3 segundos
echo $mensaje;
header("refresh:3; url=service.php");
exit;
?>