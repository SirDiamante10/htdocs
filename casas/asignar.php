<?php
require_once '../../config/conexion.php';
session_start();

$id_casa = $_POST['id_casa'];
$id_usuario = $_POST['id_usuario'];

// Validar que la casa esté disponible
$sql = "SELECT * FROM casas WHERE id_casa = ? AND estatus = 'Disponible'";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_casa]);

if ($stmt->rowCount() === 0) {
    die("La casa no está disponible o no existe.");
}

// Asignar casa al usuario
$sqlUpdate = "UPDATE casas 
              SET inquilino_id = ?, estatus = 'Ocupada'
              WHERE id_casa = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->execute([$id_usuario, $id_casa]);

header("Location: ../../comite/asignar_casa.php");
exit();
