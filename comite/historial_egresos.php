<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

// Valores por defecto: los últimos 30 días
$fecha_inicio = $_GET['inicio'] ?? date('Y-m-d', strtotime('-30 days'));
$fecha_fin    = $_GET['fin'] ?? date('Y-m-d');

// Consulta de egresos con JOIN para obtener nombre y subrol del responsable
$sql = "SELECT e.*, u.nombre, u.subrol
        FROM egresos e
        INNER JOIN usuarios u ON e.id_responsable = u.id_usuario
        WHERE e.fecha BETWEEN ? AND ?
        ORDER BY e.fecha DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$fecha_inicio, $fecha_fin]);
$egresos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de egresos</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<h2>📊 Historial de egresos</h2>

<form method="GET" action="">
  <label>Desde: <input type="date" name="inicio" value="<?= $fecha_inicio ?>" required></label>
  <label>Hasta: <input type="date" name="fin" value="<?= $fecha_fin ?>" required></label>
  <button type="submit">Filtrar</button>
</form>

<br>

<?php if (count($egresos) > 0): ?>
  <table border="1" cellpadding="6">
    <tr>
      <th>Fecha</th>
      <th>Monto</th>
      <th>Motivo</th>
      <th>Proveedor</th>
      <th>Registrado por</th>
      <th>Rol</th>
    </tr>

    <?php foreach ($egresos as $e): ?>
      <tr>
        <td><?= $e['fecha'] ?></td>
        <td>$<?= number_format($e['monto'], 2) ?></td>
        <td><?= nl2br(htmlspecialchars($e['motivo'])) ?></td>
        <td><?= htmlspecialchars($e['proveedor']) ?></td>
        <td><?= htmlspecialchars($e['nombre']) ?></td>
        <td><?= ucfirst($e['subrol']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php else: ?>
  <p>No se encontraron egresos en el rango seleccionado.</p>
<?php endif; ?>

<form method="GET" action="../php/egresos/exportar_excel.php" style="margin-top: 10px;">
  <input type="hidden" name="inicio" value="<?= $fecha_inicio ?>">
  <input type="hidden" name="fin" value="<?= $fecha_fin ?>">
  <button type="submit">📥 Descargar como archivo Excel (.csv)</button>
</form>


<p><a href="dashboard.php">← Volver al panel del comité</a></p>

</body>
</html>
