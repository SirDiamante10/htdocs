<?php
session_start();
require_once '../../config/conexion.php';

$id_usuario = $_SESSION['usuario']['id_usuario'];
$recurso = $_POST['recurso'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];

// 1. Validar que las fechas sean coherentes
if (strtotime($fecha_fin) <= strtotime($fecha_inicio)) {
    die("Error: la fecha de fin debe ser posterior a la de inicio.");
}

// 2. Validar que no haya traslape
$sql = "SELECT * FROM reservaciones 
        WHERE recurso = ?
        AND (
            (fecha_inicio < ? AND fecha_fin > ?) OR
            (fecha_inicio < ? AND fecha_fin > ?) OR
            (fecha_inicio >= ? AND fecha_fin <= ?)
        )";

$stmt = $conn->prepare($sql);
$stmt->execute([
    $recurso,
    $fecha_fin, $fecha_inicio,  // Caso de solapamiento
    $fecha_fin, $fecha_inicio,  // Caso de estar contenido
    $fecha_inicio, $fecha_fin   // Caso de estar completamente dentro
]);

if ($stmt->rowCount() > 0) {
    die("Error: ya existe una reservación para este recurso en ese horario.");
}

// 3. Insertar reservación
$sql = "INSERT INTO reservaciones (id_usuario, recurso, fecha_inicio, fecha_fin, estatus)
        VALUES (?, ?, ?, ?, 'pendiente')";

$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario, $recurso, $fecha_inicio, $fecha_fin]);

header("Location: ../../inquilino/dashboard.php");
exit();
