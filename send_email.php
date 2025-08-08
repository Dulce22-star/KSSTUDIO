

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpiar y validar los datos recibidos
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Verificar que no estén vacíos y que el email sea válido
    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Por favor completa el formulario correctamente.";
        exit;
    }

    // Tu correo donde quieres recibir los mensajes
    $recipient = "KeniStudioSpa@gmail.com";

    // Construir el correo
    $email_subject = "Nuevo mensaje de contacto: $subject";
    $email_body = "Nombre: $name\n";
    $email_body .= "Correo: $email\n\n";
    $email_body .= "Mensaje:\n$message\n";

    $headers = "From: $name <$email>";

    // Enviar el correo
    if (mail($recipient, $email_subject, $email_body, $headers)) {
        http_response_code(200);
        echo "Gracias, tu mensaje ha sido enviado.";
    } else {
        http_response_code(500);
        echo "Lo sentimos, ocurrió un error al enviar el mensaje.";
    }
} else {
    http_response_code(403);
    echo "Error: método no permitido.";
}
?>
