<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

$id_usuario = $_SESSION['usuario']['id_usuario'];

// Consultar reservaciones del inquilino
$sql = "SELECT * FROM reservaciones
        WHERE id_usuario = ?
        ORDER BY fecha_inicio DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario]);
$reservaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis reservaciones</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>Historial de reservaciones</h2>

  <?php if (count($reservaciones) > 0): ?>
    <table border="1" cellpadding="8">
      <tr>
        <th>Recurso</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Estado</th>
      </tr>

      <?php foreach ($reservaciones as $r): ?>
        <tr>
          <td><?= ucfirst($r['recurso']) ?></td>
          <td><?= $r['fecha_inicio'] ?></td>
          <td><?= $r['fecha_fin'] ?></td>
          <td>
            <?php
              switch ($r['estatus']) {
                case 'pendiente':
                  echo "⏳ Pendiente";
                  break;
                case 'aprobada':
                  echo "✅ Aprobada";
                  break;
                case 'rechazada':
                  echo "❌ Rechazada";
                  break;
              }
            ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No tienes reservaciones registradas aún.</p>
  <?php endif; ?>

  <p><a href="dashboard.php">← Volver al panel</a></p>
</body>
</html>
