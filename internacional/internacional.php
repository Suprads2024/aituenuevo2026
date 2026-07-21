<?php

// Activar errores mientras probás
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer está fuera de la carpeta /internacional/
require __DIR__ . '/../PHPMailer-master/src/Exception.php';
require __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer-master/src/SMTP.php';

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.html');
    exit;
}

// Anti-spam simple
if (!empty($_POST['website'])) {
    header('Location: /index.html');
    exit;
}

// Función para limpiar datos
function limpiar($dato) {
    $dato = trim($dato);
    $dato = strip_tags($dato);
    $dato = str_replace(["\r", "\n"], ' ', $dato);

    return htmlspecialchars($dato, ENT_QUOTES, 'UTF-8');
}

// Datos del formulario internacional
$nombre  = limpiar($_POST['nombre'] ?? '');
$empresa = limpiar($_POST['empresa'] ?? '');
$email   = trim($_POST['email'] ?? '');
$pais    = limpiar($_POST['pais'] ?? '');
$mensaje = limpiar($_POST['mensaje'] ?? '');

// Validaciones
if ($nombre === '' || $email === '') {
    exit('Por favor completá los campos obligatorios.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('El email ingresado no es válido.');
}

// Limpieza extra del email
$email = str_replace(["\r", "\n"], '', $email);

// Envío con PHPMailer
$mail = new PHPMailer(true);

try {
    $mail->CharSet = 'UTF-8';

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;

    // Cuenta SMTP que ya venían usando
    $mail->Username   = 'soporte@kerneltech.dev';

    // Acá va la App Password de esa cuenta
    $mail->Password   = 'hqyycwyzurppkzco';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // El From debe ser la misma cuenta SMTP
    $mail->setFrom($mail->Username, 'Aitue Internacional');

    // Cuando respondan, responden al usuario que completó el formulario
    $mail->addReplyTo($email, $nombre ?: $email);

    // Destinatario
    $mail->addAddress('comercial@aitue.net', 'Comercial Aitue');

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = 'Nueva consulta internacional desde la web';

    $empresaTexto = $empresa !== '' ? $empresa : 'No indicada';
    $paisTexto    = $pais !== '' ? $pais : 'No indicado';
    $mensajeTexto = $mensaje !== '' ? nl2br($mensaje) : 'El usuario no escribió un mensaje.';

    $mail->Body = "
        <h2>Nueva consulta internacional desde la web</h2>

        <p><strong>Nombre:</strong> {$nombre}</p>
        <p><strong>Empresa / Organización:</strong> {$empresaTexto}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>País / Región:</strong> {$paisTexto}</p>

        <p><strong>Mensaje:</strong><br>{$mensajeTexto}</p>
    ";

    $mail->AltBody =
        "Nueva consulta internacional desde la web\n\n" .
        "Nombre: {$nombre}\n" .
        "Empresa / Organización: {$empresaTexto}\n" .
        "Email: {$email}\n" .
        "País / Región: {$paisTexto}\n\n" .
        "Mensaje:\n{$mensaje}";

    $mail->send();

    header('Location: /gracias.html');
    exit;

} catch (Exception $e) {
    echo 'No se pudo enviar el mensaje. Error: ' . $mail->ErrorInfo;
}