<?php
session_start();
require_once '../../config/conexion.php';

$id_solicitud = $_POST['id_solicitud'];
$comentario = trim($_POST['comentario_comite']);
$estatus = $_POST['nuevo_estatus'];

// Validación simple
$estados_validos = ['en proceso', 'resuelto', 'rechazada'];
if (!in_array($estatus, $estados_validos)) {
    die("Estado inválido.");
}

// Actualizar la solicitud
$sql = "UPDATE solicitudes
        SET estatus = ?, comentario_comite = ?
        WHERE id_solicitud = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$estatus, $comentario, $id_solicitud]);

header("Location: ../../comite/solicitudes.php");
exit();
