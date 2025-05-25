<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function enviarAcusePorCorreo($destinatario, $nombreInquilino, $acusePDF, $nombreArchivo) {
    $mail = new PHPMailer(true);

    try {
        // CONFIGURACIÓN SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'dieroga19@gmail.com';         
        $mail->Password   = 'jblzxadvybqnyibu';            // ✔️ Contraseña de aplicación
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // QUIÉN ENVÍA
        $mail->setFrom('dieroga19@gmail.com', 'Cluster Admin'); 
        $mail->addAddress($destinatario, $nombreInquilino);

        // CONTENIDO DEL CORREO
        $mail->isHTML(true);
        $mail->Subject = 'Acuse de recibo de pago';
        $mail->Body    = "Hola <strong>$nombreInquilino</strong>,<br><br>Adjunto encontrarás tu acuse de pago de mantenimiento. <br><br>Gracias por mantener tus contribuciones al día.";

        // Adjuntar PDF
        $mail->addStringAttachment($acusePDF, $nombreArchivo);

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Error al enviar correo: {$mail->ErrorInfo}");
        return false;
    }
}
