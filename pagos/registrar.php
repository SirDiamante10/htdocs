<?php
session_start();
require_once '../../config/conexion.php';

$id_usuario = $_SESSION['usuario']['id_usuario'];
//$fecha_pago = $_POST['fecha_pago'] ?? null;
$fecha_pago = date('Y-m-d'); // Fecha del sistema, no manipulable

$concepto = trim($_POST['concepto'] ?? '');
$monto = floatval($_POST['monto'] ?? 0);
//$recargo = floatval($_POST['recargo'] ?? 0);
$dia = date('j'); // Día del mes sin ceros a la izquierda
$recargo = ($dia > 10) ? 50 : 0;



$mes_correspondiente = date('F Y', strtotime($fecha_pago));
$mes_correspondiente = ucfirst(strftime('%B %Y', strtotime($fecha_pago)));

if (!$fecha_pago || empty($concepto) || $monto <= 0) {
    die("❌ Faltan datos del formulario.");
}


$sql = "INSERT INTO pagos (id_usuario, fecha_pago, concepto, mes_correspondiente, monto, recargo)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario, $fecha_pago, $concepto, $mes_correspondiente, $monto, $recargo]);

header("Location: ../../inquilino/dashboard.php");
exit();
