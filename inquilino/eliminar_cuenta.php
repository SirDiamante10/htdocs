<?php
session_start();
require_once '../config/conexion.php';

$id_usuario = $_SESSION['usuario']['id_usuario'];
$mesActual = date('F');

// 1. Verificar si el usuario está vinculado a una casa
$sqlCasa = "SELECT * FROM casas WHERE inquilino_id = ?";
$stmtCasa = $conn->prepare($sqlCasa);
$stmtCasa->execute([$id_usuario]);
if ($stmtCasa->rowCount() > 0) {
    die("❌ No puedes eliminar tu cuenta. Primero debes darte de baja como inquilino.");
}

// 2. Verificar pagos pendientes del mes
$sqlPago = "SELECT * FROM pagos 
            WHERE id_usuario = ? AND mes_correspondiente = ? AND verificado = 0";
$stmtPago = $conn->prepare($sqlPago);
$stmtPago->execute([$id_usuario, $mesActual]);
if ($stmtPago->rowCount() > 0) {
    die("❌ No puedes eliminar tu cuenta. Tienes pagos pendientes este mes.");
}

// 3. Verificar reservaciones activas
$sqlRes = "SELECT * FROM reservaciones 
           WHERE id_usuario = ? AND fecha_fin >= NOW() AND estatus = 'aprobada'";
$stmtRes = $conn->prepare($sqlRes);
$stmtRes->execute([$id_usuario]);
if ($stmtRes->rowCount() > 0) {
    die("❌ Tienes reservaciones activas. Cancélalas antes de eliminar tu cuenta.");
}

// 4. Verificar solicitudes en proceso
$sqlSol = "SELECT * FROM solicitudes 
           WHERE id_usuario = ? AND estatus IN ('pendiente', 'en proceso')";
$stmtSol = $conn->prepare($sqlSol);
$stmtSol->execute([$id_usuario]);
if ($stmtSol->rowCount() > 0) {
    die("❌ Tienes solicitudes activas. Espera a que se resuelvan antes de eliminar tu cuenta.");
}

// ✅ Si pasa validaciones, eliminar usuario
$sqlEliminar = "DELETE FROM usuarios WHERE id_usuario = ?";
$stmtEliminar = $conn->prepare($sqlEliminar);
$stmtEliminar->execute([$id_usuario]);

// Cerrar sesión y redirigir
session_destroy();
header("Location: ../login.php?eliminado=ok");
exit();
