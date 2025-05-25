<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

// Consulta de solicitudes activas o recientes (menos de 1 hora)
$sql = "SELECT s.*, u.nombre AS inquilino, u.rol
        FROM solicitudes s
        INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
        WHERE 
            s.estatus IN ('pendiente', 'en proceso')
            OR (
                s.estatus IN ('resuelto', 'rechazada') 
                AND TIMESTAMPDIFF(MINUTE, s.fecha, NOW()) <= 60
            )
        ORDER BY s.fecha DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$solicitudes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Foro de Solicitudes</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>📢 Foro de solicitudes activas</h2>

  <?php if (count($solicitudes) > 0): ?>
    <table border="1" cellpadding="8">
      <tr>
        <th>Inquilino</th>
        <th>Fecha</th>
        <th>Mensaje</th>
        <th>Estado</th>
        <th>Comentario del comité</th>
      </tr>

      <?php foreach ($solicitudes as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['inquilino']) ?></td>
          <td><?= $s['fecha'] ?></td>
          <td><?= nl2br(htmlspecialchars($s['mensaje'])) ?></td>
          <td>
            <?php
              switch ($s['estatus']) {
                case 'pendiente': echo '⏳ Pendiente'; break;
                case 'en proceso': echo '🛠️ En proceso'; break;
                case 'resuelto': echo '✅ Cumplida'; break;
                case 'rechazada': echo '❌ Rechazada'; break;
              }
            ?>
          </td>
          <td><?= nl2br(htmlspecialchars($s['comentario_comite'] ?? '')) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p>No hay solicitudes activas actualmente.</p>
  <?php endif; ?>

  <p><a href="dashboard.php">← Volver al panel principal</a></p>
</body>
</html>


<hr>
<h3>💬 Chat general del foro</h3>

<!-- Formulario de nuevo mensaje -->
<form action="../php/chat/enviar.php" method="POST">
  <textarea name="mensaje" rows="3" cols="60" placeholder="Escribe tu mensaje aquí..." required></textarea><br>
  <button type="submit">Enviar</button>
</form>


<?php
// Mostrar solo mensajes de los últimos 7 días
$sql = "SELECT c.*, u.nombre, u.rol
        FROM chat_foro c
        INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
        WHERE c.fecha >= NOW() - INTERVAL 7 DAY
        ORDER BY c.fecha DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$mensajes = $stmt->fetchAll();
?>

<?php if (count($mensajes) > 0): ?>
  <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ccc; margin-top: 10px; padding: 10px;">
    <?php foreach ($mensajes as $m): ?>
      <p><strong><?= htmlspecialchars($m['nombre']) ?> (<?= $m['rol'] ?>)</strong> 
      <em><?= date('d/m/Y H:i', strtotime($m['fecha'])) ?></em><br>
      <?= nl2br(htmlspecialchars($m['mensaje'])) ?></p>
      <hr>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <p>No hay mensajes aún.</p>
<?php endif; ?>

