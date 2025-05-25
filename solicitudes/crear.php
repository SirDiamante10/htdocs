<?php
session_start();
require_once '../../config/conexion.php';

$id_usuario = $_SESSION['usuario']['id_usuario'];
$mensaje = trim($_POST['mensaje']);
$fecha = date("Y-m-d H:i:s");

// Insertar solicitud
$sql = "INSERT INTO solicitudes (id_usuario, mensaje, fecha, estatus, comentario_comite)
        VALUES (?, ?, ?, 'pendiente', NULL)";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario, $mensaje, $fecha]);

header("Location: ../../inquilino/dashboard.php");
exit();
