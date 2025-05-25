<?php
require_once '../../config/conexion.php';

$inicio = $_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days'));
$fin    = $_GET['fin'] ?? date('Y-m-d');

// Consultar egresos con datos del responsable
$sql = "SELECT e.fecha, e.monto, e.motivo, e.proveedor, u.nombre, u.subrol
        FROM egresos e
        INNER JOIN usuarios u ON e.id_responsable = u.id_usuario
        WHERE e.fecha BETWEEN ? AND ?
        ORDER BY e.fecha DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$inicio, $fin]);
$egresos = $stmt->fetchAll();

// Encabezado para descarga de archivo
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=egresos_'.$inicio.'_al_'.$fin.'.csv');

// Salida estándar como archivo CSV
$output = fopen('php://output', 'w');

// Encabezados de columnas
fputcsv($output, ['Fecha', 'Monto', 'Motivo', 'Proveedor', 'Responsable', 'Subrol']);

// Filas de datos
foreach ($egresos as $e) {
    fputcsv($output, [
        $e['fecha'],
        number_format($e['monto'], 2),
        $e['motivo'],
        $e['proveedor'],
        $e['nombre'],
        ucfirst($e['subrol'])
    ]);
}

fclose($output);
exit();
