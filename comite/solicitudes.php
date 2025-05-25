<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

$filtro = $_GET['filtro'] ?? 'todas';

// Construir condición según el filtro
$where = '';
switch ($filtro) {
    case 'pendientes':
        $where = "WHERE s.estatus = 'pendiente'";
        break;
    case 'enproceso':
        $where = "WHERE s.estatus = 'en proceso'";
        break;
    case 'cumplidas':
        $where = "WHERE s.estatus = 'resuelto'";
        break;
    case 'rechazadas':
        $where = "WHERE s.estatus = 'rechazada'";
        break;
    default:
        $where = ""; // Todas
        break;
}

$sql = "SELECT s.*, u.nombre AS inquilino
        FROM solicitudes s
        INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
        $where
        ORDER BY s.fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$solicitudes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitudes de servicio</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>Solicitudes de servicio al comité</h2>

  <p>Filtrar por:
    <a href="?filtro=todas">Todas</a> |
    <a href="?filtro=pendientes">Pendientes</a> |
    <a href="?filtro=enproceso">En proceso</a> |
    <a href="?filtro=cumplidas">Cumplidas</a> |
    <a href="?filtro=rechazadas">Rechazadas</a>
  </p>

  <?php if (count($solicitudes) > 0): ?>
    <table border="1" cellpadding="8">
      <tr>
        <th>Inquilino</th>
        <th>Fecha</th>
        <th>Mensaje</th>
        <th>Estado</th>
        <th>Comentario del comité</th>
        <th>Acción</th>
      </tr>

      <?php foreach ($solicitudes as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['inquilino']) ?></td>
          <td><?= $s['fecha'] ?></td>
          <td><?= nl2br(htmlspecialchars($s['mensaje'])) ?></td>
          <td><?= ucfirst($s['estatus']) ?></td>
          <td><?= nl2br(htmlspecialchars($s['comentario_comite'] ?? '')) ?></td>
          <td>
            <?php if ($s['estatus'] === 'pendiente' || $s['estatus'] === 'en proceso'): ?>
              <form action="../php/solicitudes/actualizar.php" method="POST">
                <input type="hidden" name="id_solicitud" value="<?= $s['id_solicitud'] ?>">
                <textarea name="comentario_comite" rows="3" cols="30" placeholder="Respuesta del comité" required></textarea><br>
                <select name="nuevo_estatus" required>
                  <option value="en proceso" <?= $s['estatus'] === 'en proceso' ? 'selected' : '' ?>>Aceptar - En curso</option>
                  <option value="resuelto">Aceptar - Cumplida</option>
                  <option value="rechazada">Rechazar</option>
                </select><br><br>
                <button type="submit">Actualizar</button>
              </form>
            <?php else: ?>
              <em>Sin acciones disponibles</em>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No hay solicitudes que coincidan con este filtro.</p>
  <?php endif; ?>

  <p><a href="dashboard.php">← Volver al panel</a></p>
</body>
</html>
