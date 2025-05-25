<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

// Consultar reservaciones pendientes
$sql = "SELECT r.*, u.nombre AS inquilino
        FROM reservaciones r
        INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
        WHERE r.estatus = 'pendiente'
        ORDER BY r.fecha_inicio ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$reservaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Validar reservaciones</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>Reservaciones pendientes</h2>

  <?php if (count($reservaciones) > 0): ?>
    <table border="1" cellpadding="8">
      <tr>
        <th>Inquilino</th>
        <th>Recurso</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Acciones</th>
      </tr>

      <?php foreach ($reservaciones as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['inquilino']) ?></td>
          <td><?= ucfirst($r['recurso']) ?></td>
          <td><?= $r['fecha_inicio'] ?></td>
          <td><?= $r['fecha_fin'] ?></td>
          <td>
            <form action="../php/reservaciones/aprobar.php" method="POST" style="display:inline;">
              <input type="hidden" name="id_reservacion" value="<?= $r['id_reservacion'] ?>">
              <button type="submit">Aprobar</button>
            </form>
            <form action="../php/reservaciones/rechazar.php" method="POST" style="display:inline;">
              <input type="hidden" name="id_reservacion" value="<?= $r['id_reservacion'] ?>">
              <button type="submit">Rechazar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No hay reservaciones pendientes por validar.</p>
  <?php endif; ?>

  <p><a href="historial_reservaciones.php">Historial de reservaciones</a></p>
  
  <p><a href="dashboard.php">← Volver al panel</a></p>
</body>
</html>
