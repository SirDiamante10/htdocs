<?php
session_start();
require_once '../../config/conexion.php';

$id_usuario = $_SESSION['usuario']['id_usuario'];
$mensaje = trim($_POST['mensaje']);
$fecha = date("Y-m-d H:i:s");

if ($mensaje === '') {
    header("Location: ../../inquilino/foro_solicitudes.php");
    exit();
}

$sql = "INSERT INTO chat_foro (id_usuario, mensaje, fecha)
        VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario, $mensaje, $fecha]);

header("Location: ../../inquilino/foro_solicitudes.php");
exit();
