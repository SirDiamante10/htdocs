<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

// Mostrar solo reservaciones aprobadas y futuras
$sql = "SELECT r.*, u.nombre AS inquilino
        FROM reservaciones r
        INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
        WHERE r.estatus = 'aprobada' AND r.fecha_fin >= NOW()
        ORDER BY r.fecha_inicio ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$reservaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reservaciones activas</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>Reservaciones activas de palapa y alberca</h2>

  <?php if (count($reservaciones) > 0): ?>
    <table border="1" cellpadding="8">
      <tr>
        <th>Inquilino</th>
        <th>Recurso</th>
        <th>Inicio</th>
        <th>Fin</th>
      </tr>

      <?php foreach ($reservaciones as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['inquilino']) ?></td>
          <td><?= ucfirst($r['recurso']) ?></td>
          <td><?= $r['fecha_inicio'] ?></td>
          <td><?= $r['fecha_fin'] ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No hay reservaciones activas registradas.</p>
  <?php endif; ?>

  <p><a href="dashboard.php">← Volver al panel</a></p>
</body>
</html>
