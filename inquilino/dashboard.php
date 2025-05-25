<?php
require_once '../includes/auth.php';
$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Inquilino</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>Hola, <?php echo htmlspecialchars($usuario['nombre']); ?> 🏠</h2>
  <p>Rol: <?php echo $usuario['rol']; ?></p>

  <ul>
    <li><a href="mi_cuenta.php">Cuenta</a></li>
    <li><a href="pagos.php">Pagos e historial</a></li>
    <li><a href="reservaciones.php">Reservaciones de espacios</a></li>
    <li><a href="solicitud_servicio.php">Solicitar servicio al comité</a></li>
    <li><a href="../php/auth/logout.php">Cerrar sesión</a></li>
  </ul>
</body>
</html>


<?php
// Conexión y consulta para mostrar solo las 3 solicitudes activas más recientes
require_once '../config/conexion.php';

$sql = "SELECT s.*, u.nombre AS inquilino, u.rol
        FROM solicitudes s
        INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
        WHERE 
            s.estatus IN ('pendiente', 'en proceso')
            OR (
                s.estatus IN ('resuelto', 'rechazada') 
                AND TIMESTAMPDIFF(MINUTE, s.fecha, NOW()) <= 60
            )
        ORDER BY s.fecha DESC
        LIMIT 3";

$stmt = $conn->prepare($sql);
$stmt->execute();
$ultimasSolicitudes = $stmt->fetchAll();
?>

<hr>
<h3>📌 Solicitudes activas recientes</h3>

<?php if (count($ultimasSolicitudes) > 0): ?>
  <table border="1" cellpadding="6">
    <tr>
      <th>Inquilino</th>
      <th>Fecha</th>
      <th>Mensaje</th>
      <th>Estado</th>
      <th>Comentario</th>
    </tr>
    <?php foreach ($ultimasSolicitudes as $s): ?>
    <tr>
      <td><?= htmlspecialchars($s['inquilino']) ?> </td>
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

  <p><a href="foro_solicitudes.php">Ver foro completo de solicitudes →</a></p>
<?php else: ?>
  <p>No hay solicitudes activas recientes.</p>
<?php endif; ?>
