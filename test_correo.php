<?php
require_once 'vendor/autoload.php';
require_once 'config/email.php';

// Crear PDF de prueba
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml("<h2>Este es un acuse de prueba</h2><p>Probando envío de PDF por correo.</p>");
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$pdf = $dompdf->output();

// Enviar
$correoDestino = 'diamate10@gmail.com';  // Reemplaza con un correo real tuyo
$nombreDestino = 'PRUEBA GIGANTE';

$exito = enviarAcusePorCorreo($correoDestino, $nombreDestino, $pdf, 'acuse_prueba.pdf');

if ($exito) {
    echo "✅ Correo enviado exitosamente a $correoDestino";
} else {
    echo "❌ Error al enviar el correo. Revisa el error_log.";
}
