<?php
session_start();
require_once '../config/conexion.php';

$id_usuario = $_SESSION['usuario']['id_usuario'];

// Verificar que está vinculado a una casa
$sqlCasa = "SELECT * FROM casas WHERE inquilino_id = ?";
$stmtCasa = $conn->prepare($sqlCasa);
$stmtCasa->execute([$id_usuario]);
$casa = $stmtCasa->fetch();

if (!$casa) {
    die("❌ No estás vinculado actualmente a ninguna casa.");
}

$id_casa = $casa['id_casa'];
$mesActual = date('F');

// Verificar pagos pendientes
$sqlPago = "SELECT * FROM pagos WHERE id_usuario = ? AND mes_correspondiente = ? AND verificado = 0";
$stmtPago = $conn->prepare($sqlPago);
$stmtPago->execute([$id_usuario, $mesActual]);
if ($stmtPago->rowCount() > 0) {
    die("❌ No puedes solicitar baja. Tienes pagos pendientes este mes.");
}

// Verificar reservaciones futuras
$sqlRes = "SELECT * FROM reservaciones WHERE id_usuario = ? AND fecha_fin >= NOW() AND estatus = 'aprobada'";
$stmtRes = $conn->prepare($sqlRes);
$stmtRes->execute([$id_usuario]);
if ($stmtRes->rowCount() > 0) {
    die("❌ Tienes reservaciones activas. Cancélalas antes de darte de baja.");
}

// Verificar solicitudes en curso
$sqlSol = "SELECT * FROM solicitudes WHERE id_usuario = ? AND estatus IN ('pendiente', 'en proceso')";
$stmtSol = $conn->prepare($sqlSol);
$stmtSol->execute([$id_usuario]);
if ($stmtSol->rowCount() > 0) {
    die("❌ Tienes solicitudes activas. Espera a que se resuelvan antes de darte de baja.");
}

// Liberar casa
$sqlLiberar = "UPDATE casas SET inquilino_id = NULL, estatus = 'Disponible' WHERE id_casa = ?";
$stmtLiberar = $conn->prepare($sqlLiberar);
$stmtLiberar->execute([$id_casa]);

// Redirigir con éxito
header("Location: mi_cuenta.php?baja=ok");
exit();
