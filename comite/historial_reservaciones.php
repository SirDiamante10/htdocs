<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

$filtro = $_GET['filtro'] ?? 'todas';

// Armar consulta dinámica según el filtro
$where = '';
switch ($filtro) {
    case 'activas':
        $where = "WHERE r.estatus = 'aprobada' AND r.fecha_fin >= NOW()";
        break;
    case 'finalizadas':
        $where = "WHERE r.estatus = 'aprobada' AND r.fecha_fin < NOW()";
        break;
    case 'pendientes':
        $where = "WHERE r.estatus = 'pendiente'";
        break;
    case 'rechazadas':
        $where = "WHERE r.estatus = 'rechazada'";
        break;
    default:
        $where = ""; // Mostrar todas
        break;
}

// Ejecutar consulta
$sql = "SELECT r.*, u.nombre AS inquilino
        FROM reservaciones r
        INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
        $where
        ORDER BY r.fecha_inicio DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$reservaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de reservaciones</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>Historial de reservaciones</h2>

  <p>Filtrar por:
    <a href="?filtro=todas">Todas</a> |
    <a href="?filtro=activas">Activas</a> |
    <a href="?filtro=finalizadas">Finalizadas</a> |
    <a href="?filtro=pendientes">Pendientes</a> |
    <a href="?filtro=rechazadas">Rechazadas</a>
  </p>

  <?php if (count($reservaciones) > 0): ?>
    <table border="1" cellpadding="8">
      <tr>
        <th>Inquilino</th>
        <th>Recurso</th>
        <th>Inicio</th>
        <th>Fin</th>
        <th>Estado</th>
      </tr>

      <?php foreach ($reservaciones as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['inquilino']) ?></td>
          <td><?= ucfirst($r['recurso']) ?></td>
          <td><?= $r['fecha_inicio'] ?></td>
          <td><?= $r['fecha_fin'] ?></td>
          <td>
            <?php
              switch ($r['estatus']) {
                case 'pendiente': echo "⏳ Pendiente"; break;
                case 'aprobada':
                  echo ($r['fecha_fin'] >= date('Y-m-d H:i:s')) ? "✅ Activa" : "✅ Finalizada";
                  break;
                case 'rechazada': echo "❌ Rechazada"; break;
              }
            ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No hay reservaciones que coincidan con este filtro.</p>
  <?php endif; ?>

  <p><a href="dashboard.php">← Volver al panel</a></p>
</body>
</html>
