<?php

require '../src/Exception.php';
require '../src/PHPMailer.php';
require '../src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'msg' => 'Método no permitido']);
    exit;
}

$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$mensaje = $_POST['mensaje'] ?? '';

if (!$nombre || !$email || !$mensaje) {
    echo json_encode(['ok' => false, 'msg' => 'Faltan datos obligatorios']);
    exit;
}

$mail = new PHPMailer(true);

try {
    
    $mail->isSMTP();
    $mail->Host = 'in-v3.mailjet.com';
    $mail->SMTPAuth = true;
    $mail->Username = '4af133aace02100320a24a03f963c52d';  
    $mail->Password = '97fcf3822f8ced0e9fa57ca7e413c987';  
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('dayanaugaz19@gmail.com', 'Turismo Pancracio');
    $mail->addAddress('dayanaugaz2007@gmail.com', 'Dayana Ugaz');
    
    $mail->addReplyTo($email, $nombre);


    $mail->isHTML(true);
    $mail->Subject = 'Nuevo mensaje de contacto';
    $mail->Body = "
        <h3>Nuevo mensaje desde el formulario de contacto</h3>
        <p><strong>Nombre:</strong> {$nombre}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Mensaje:</strong><br />" . nl2br(htmlspecialchars($mensaje)) . "</p>
    ";

    $mail->send();

    echo json_encode(['ok' => true, 'msg' => 'Mensaje enviado correctamente']);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'msg' => "No se pudo enviar el mensaje. Error: {$mail->ErrorInfo}"]);
}
?>
