<?php
require_once '../../config/conexion.php';

$año = $_GET['año'] ?? date('Y');
$mes = $_GET['mes'] ?? '';

// Determinar rango de fechas
if ($mes) {
    $fecha_inicio = "$año-$mes-01";
    $fecha_fin = date("Y-m-t", strtotime($fecha_inicio));
    $nombre_reporte = "Reporte_$año-$mes";
} else {
    $fecha_inicio = "$año-01-01";
    $fecha_fin = "$año-12-31";
    $nombre_reporte = "Reporte_$año";
}

// Obtener pagos
$sqlPagos = "SELECT p.*, u.nombre
             FROM pagos p
             INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
             WHERE p.verificado = 1 AND p.fecha_pago BETWEEN ? AND ?";
$stmt = $conn->prepare($sqlPagos);
$stmt->execute([$fecha_inicio, $fecha_fin]);
$pagos = $stmt->fetchAll();

$total_ingresos = 0;
foreach ($pagos as $p) {
    $total_ingresos += $p['monto'] + $p['recargo'];
}

// Obtener egresos
$sqlEgresos = "SELECT e.*, u.nombre AS responsable, u.subrol
               FROM egresos e
               INNER JOIN usuarios u ON e.id_responsable = u.id_usuario
               WHERE e.fecha BETWEEN ? AND ?";
$stmt = $conn->prepare($sqlEgresos);
$stmt->execute([$fecha_inicio, $fecha_fin]);
$egresos = $stmt->fetchAll();

$total_egresos = 0;
foreach ($egresos as $e) {
    $total_egresos += $e['monto'];
}

$balance = $total_ingresos - $total_egresos;

// Encabezados de descarga
header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=$nombre_reporte.csv");

$output = fopen('php://output', 'w');

// --- RESUMEN FINANCIERO ---
fputcsv($output, ["Resumen financiero"]);
fputcsv($output, ["Periodo", "$fecha_inicio al $fecha_fin"]);
fputcsv($output, ["Total ingresos", number_format($total_ingresos, 2)]);
fputcsv($output, ["Total egresos", number_format($total_egresos, 2)]);
fputcsv($output, ["Balance", number_format($balance, 2)]);
fputcsv($output, []);

// --- DETALLE DE PAGOS ---
fputcsv($output, ["Pagos registrados"]);
fputcsv($output, ["Fecha", "Inquilino", "Mes correspondiente", "Monto", "Recargo", "Total"]);

foreach ($pagos as $p) {
    $total = $p['monto'] + $p['recargo'];
    fputcsv($output, [
        $p['fecha_pago'],
        $p['nombre'],
        $p['mes_correspondiente'],
        number_format($p['monto'], 2),
        number_format($p['recargo'], 2),
        number_format($total, 2)
    ]);
}
fputcsv($output, []);

// --- DETALLE DE EGRESOS ---
fputcsv($output, ["Egresos registrados"]);
fputcsv($output, ["Fecha", "Proveedor", "Monto", "Motivo", "Responsable", "Subrol"]);

foreach ($egresos as $e) {
    fputcsv($output, [
        $e['fecha'],
        $e['proveedor'],
        number_format($e['monto'], 2),
        $e['motivo'],
        $e['responsable'],
        ucfirst($e['subrol'])
    ]);
}

fclose($output);
exit();
