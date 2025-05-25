<?php
require_once '../../config/conexion.php';
require_once '../../vendor/autoload.php'; // Asegúrate que DOMPDF esté disponible
use Dompdf\Dompdf;

session_start();
$id_pago = $_GET['id_pago'] ?? 0;

// Consultar la información del pago, usuario e identificador del verificador
$sql = "SELECT p.*, u.nombre AS inquilino, c.nombre AS verificador
        FROM pagos p
        INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
        LEFT JOIN usuarios c ON p.id_verificador = c.id_usuario
        WHERE p.id_pago = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$id_pago]);
$pago = $stmt->fetch();

// Si no se encontró el pago o no está verificado aún, salir
if (!$pago || $pago['verificado'] != 1) {
    die("Este pago no ha sido verificado aún o no existe.");
}

// Generar HTML del recibo
$html = "
<h2>Acuse de Recibo</h2>
<p>Inquilino: <strong>{$pago['inquilino']}</strong></p>
<p>Fecha de pago: {$pago['fecha_pago']}</p>
<p>Mes correspondiente: {$pago['mes_correspondiente']}</p>
<p>Monto: $".number_format($pago['monto'], 2)."</p>
<p>Recargo: $".number_format($pago['recargo'], 2)."</p>
<p>Fecha de verificación: {$pago['fecha_verificacion']}</p>
<p>Verificado por: <strong>{$pago['verificador']}</strong></p>
<hr>
<p><em>Gracias por cumplir con su cuota de mantenimiento.</em></p>
";

// Crear el PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Descargar en el navegador
$dompdf->stream("acuse_pago_{$pago['id_pago']}.pdf", ["Attachment" => true]);
exit;
