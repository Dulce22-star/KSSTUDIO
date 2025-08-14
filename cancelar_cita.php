<?php
include 'includes/conexion.php';

// Verificamos si llegó el id de la cita por GET
if(isset($_GET['id'])){
    $id_cita = $_GET['id'];

    try {
        // Cambiamos el estado de la cita a "Cancelada"
        $stmt = $conexion->prepare("UPDATE citas SET estado = 'Cancelada' WHERE id_cita = :id_cita");
        $stmt->bindParam(':id_cita', $id_cita);
        $stmt->execute();

        // Redirigimos de vuelta a service.php
        header("Location: service.php");
        exit;

    } catch(PDOException $e){
        echo "Error al cancelar la cita: " . $e->getMessage();
    }

} else {
    echo "No se especificó la cita a cancelar.";
}
?>