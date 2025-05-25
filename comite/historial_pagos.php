<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

// Consulta todos los pagos
$sql = "SELECT p.*, u.nombre AS inquilino 
        FROM pagos p
        INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
        ORDER BY fecha_pago ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$pagos = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de pagos (comité)</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>Historial completo de pagos</h2>

  <table border="1" cellpadding="8">
    <tr>
      <th>Inquilino</th>
      <th>Fecha de pago</th>
      <th>Mes</th>
      <th>Concepto</th>
      <th>Monto</th>
      <th>Recargo</th>
      <th>Verificado</th>
      <th>Ver acuse</th>
    </tr>

    <?php foreach ($pagos as $p): ?>
    <tr>
      <td><?= htmlspecialchars($p['inquilino']) ?></td>
      <td><?= $p['fecha_pago'] ?></td>
      <td><?= $p['mes_correspondiente'] ?></td>
      <td><?= htmlspecialchars($p['concepto']) ?></td>
      <td>$<?= number_format($p['monto'], 2) ?></td>
      <td>$<?= number_format($p['recargo'], 2) ?></td>
      <td><?= $p['verificado'] ? "✅" : "❌" ?></td>
      <td>
        <?php if ($p['verificado']): ?>
          <a href="../php/pagos/generar_recibo.php?id_pago=<?= $p['id_pago'] ?>" target="_blank">PDF</a>
        <?php else: ?>
          No disponible
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>

  <p><a href="dashboard.php">← Volver al panel</a></p>
</body>
</html>
