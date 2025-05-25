<?php
session_start();
require_once '../../config/conexion.php';

// Validar acceso: solo comité
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'comite') {
    die("❌ Acceso no autorizado.");
}

$id_responsable = $_SESSION['usuario']['id_usuario'];
$monto = isset($_POST['monto']) ? floatval($_POST['monto']) : 0;
$fecha = $_POST['fecha'] ?? '';
$motivo = trim($_POST['motivo'] ?? '');
$proveedor = trim($_POST['proveedor'] ?? '');

// Validaciones 
$errores = [];

if ($monto <= 0) $errores[] = "El monto debe ser mayor que cero.";
if (empty($fecha)) $errores[] = "Debes indicar la fecha.";
if (empty($motivo)) $errores[] = "El motivo del egreso es obligatorio.";
if (empty($proveedor)) $errores[] = "Debes indicar a quién se le pagó.";

if (!empty($errores)) {
    foreach ($errores as $e) {
        echo "<p style='color:red;'>❌ $e</p>";
    }
    echo "<p><a href='../../comite/egresos.php'>← Volver al formulario</a></p>";
    exit();
}

// Insertar egreso en la base de datos
$sql = "INSERT INTO egresos (fecha, monto, motivo, proveedor, id_responsable)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->execute([$fecha, $monto, $motivo, $proveedor, $id_responsable]);

// Redirigir con confirmación
header("Location: ../../comite/egresos.php?registro=ok");
exit();
