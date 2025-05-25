<?php
session_start();
require_once '../../config/conexion.php';
require_once '../../config/email.php';
require_once '../../vendor/autoload.php';
use Dompdf\Dompdf;

$id_pago = $_POST['id_pago'];
$id_verificador = $_SESSION['usuario']['id_usuario'];

// Actualizar el pago como verificado
$sql = "UPDATE pagos
        SET verificado = 1, fecha_verificacion = NOW(), id_verificador = ?
        WHERE id_pago = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_verificador, $id_pago]);

// Obtener datos del pago + usuario
$sql = "SELECT 
  p.*, 
  u.nombre AS nombre_inquilino,
  u.email AS email_inquilino,
  c.nombre AS nombre_verificador,
  c.subrol AS subrol_verificador
FROM pagos p
JOIN usuarios u ON p.id_usuario = u.id_usuario
LEFT JOIN usuarios c ON p.id_verificador = c.id_usuario
WHERE p.id_pago = ?
";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_pago]);
$pago = $stmt->fetch();

// Crear HTML del PDF
$html = "
<h2>Acuse de Recibo</h2>
<p>Inquilino: <strong>{$nombreInquilino}</strong></p>
<p>Fecha de pago: {$pago['fecha_pago']}</p>
<p>Mes correspondiente: {$pago['mes_correspondiente']}</p>
<p>Monto: $".number_format($pago['monto'], 2)."</p>
<p>Recargo: $".number_format($pago['recargo'], 2)."</p>
<p>Fecha de verificación: {$pago['fecha_verificacion']}</p>
<p>Verificado por: <strong>{$verificador} ({$subrol})</strong></p>
<p>Gracias por cumplir con su cuota de mantenimiento</p>
";



// Crear PDF con DOMPDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$pdf = $dompdf->output();

$nombreInquilino = $pago['nombre_inquilino'];
$emailInquilino  = $pago['email_inquilino'];
$verificador     = $pago['nombre_verificador'];
$subrol          = ucfirst($pago['subrol_verificador']);
//$correoDestino = $pago['email'];
//$nombreDestino = $pago['inquilino'];
$nombreArchivo = "acuse_pago_{$id_pago}.pdf";
// Enviar el correo con PDF adjunto
$ok = enviarAcusePorCorreo($emailInquilino, $nombreInquilino, $pdf, $nombreArchivo);
//$ok = enviarAcusePorCorreo($correoDestino, $nombreDestino, $pdf, $nombreArchivo);
//$ok = enviarAcusePorCorreo($pago['email'], $pago['inquilino'], $pdf, "acuse_pago_{$id_pago}.pdf");

if ($ok) {
    echo "✅ Correo enviado a $correoDestino";
} else {
    echo "❌ Error al enviar el correo";
}


header("Location: ../../comite/pagos.php");
exit();
