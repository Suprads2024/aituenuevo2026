<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

// Anti-spam simple
if (!empty($_POST['website'])) {
    header('Location: index.html');
    exit;
}

function limpiar($dato) {
    $dato = trim($dato);
    $dato = strip_tags($dato);
    $dato = str_replace(["\r", "\n"], ' ', $dato);
    return $dato;
}

// Datos del formulario
$nombre  = limpiar($_POST['nombre'] ?? '');
$empresa = limpiar($_POST['empresa'] ?? '');
$email   = limpiar($_POST['email'] ?? '');
$pais    = limpiar($_POST['pais'] ?? '');
$mensaje = limpiar($_POST['mensaje'] ?? '');

// Validaciones
if ($nombre === '' || $email === '') {
    exit('Por favor completá los campos obligatorios.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('El email ingresado no es válido.');
}

// Correo donde llega el formulario
$destinatario = 'comercial@aitue.net';

// Correo remitente del dominio
$remitente = 'formularios@aitue.net';

// Asunto
$asunto = 'Nueva consulta internacional desde la web';

// Contenido del mail
$contenido  = "Recibiste una nueva consulta desde el formulario de proyecto.\n\n";
$contenido .= "Nombre: {$nombre}\n";

if ($empresa !== '') {
    $contenido .= "Empresa / Organización: {$empresa}\n";
} else {
    $contenido .= "Empresa / Organización: No indicada\n";
}

$contenido .= "Email: {$email}\n";

if ($pais !== '') {
    $contenido .= "País / Región: {$pais}\n";
} else {
    $contenido .= "País / Región: No indicado\n";
}

$contenido .= "\nMensaje:\n";

if ($mensaje !== '') {
    $contenido .= "{$mensaje}\n";
} else {
    $contenido .= "El usuario no escribió un mensaje.\n";
}

// Cabeceras
$cabeceras  = "From: Formulario web Aitue <{$remitente}>\r\n";
$cabeceras .= "Reply-To: {$nombre} <{$email}>\r\n";
$cabeceras .= "MIME-Version: 1.0\r\n";
$cabeceras .= "Content-Type: text/plain; charset=UTF-8\r\n";
$cabeceras .= "Content-Transfer-Encoding: 8bit\r\n";
$cabeceras .= "X-Mailer: PHP/" . phpversion();

// Enviar
$enviado = mail(
    $destinatario,
    $asunto,
    $contenido,
    $cabeceras,
    "-f{$remitente}"
);

if ($enviado) {
    header('Location: gracias.html');
    exit;
}

http_response_code(500);
exit('No se pudo enviar el mensaje. Intentá nuevamente más tarde.');