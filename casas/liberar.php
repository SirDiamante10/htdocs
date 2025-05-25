<?php
session_start();
require_once '../../config/conexion.php';

$id_casa = $_POST['id_casa'];
$id_usuario = $_POST['id_usuario'];

// 1. Verificar pagos pendientes
$mesActual = date('F');
$sqlPagos = "SELECT * FROM pagos 
             WHERE id_usuario = ? AND mes_correspondiente = ? AND verificado = 0";
$stmtPagos = $conn->prepare($sqlPagos);
$stmtPagos->execute([$id_usuario, $mesActual]);
if ($stmtPagos->rowCount() > 0) {
    die("❌ No se puede liberar la casa. El inquilino tiene pagos pendientes este mes.");
}

// 2. Verificar reservaciones futuras
$sqlRes = "SELECT * FROM reservaciones 
           WHERE id_usuario = ? AND fecha_fin >= NOW() AND estatus = 'aprobada'";
$stmtRes = $conn->prepare($sqlRes);
$stmtRes->execute([$id_usuario]);
if ($stmtRes->rowCount() > 0) {
    die("❌ No se puede liberar. El inquilino tiene reservaciones activas.");
}

// 3. Verificar solicitudes pendientes
$sqlSol = "SELECT * FROM solicitudes 
           WHERE id_usuario = ? AND estatus IN ('pendiente', 'en proceso')";
$stmtSol = $conn->prepare($sqlSol);
$stmtSol->execute([$id_usuario]);
if ($stmtSol->rowCount() > 0) {
    die("❌ No se puede liberar. El inquilino tiene solicitudes activas.");
}

// Si pasa validaciones: liberar
$sqlLiberar = "UPDATE casas 
               SET inquilino_id = NULL, estatus = 'Disponible'
               WHERE id_casa = ?";
$stmtLiberar = $conn->prepare($sqlLiberar);
$stmtLiberar->execute([$id_casa]);

header("Location: ../../comite/liberar_casa.php");
exit();
