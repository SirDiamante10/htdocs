<?php
require_once '../../config/conexion.php';
session_start();

$id = $_POST['id_reservacion'];

$sql = "UPDATE reservaciones SET estatus = 'aprobada' WHERE id_reservacion = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

header("Location: ../../comite/reservaciones.php");
exit();
