<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

$id_usuario = $_SESSION['usuario']['id_usuario'];

// Consultar solo pagos de este inquilino
$sql = "SELECT * FROM pagos
        WHERE id_usuario = ?
        ORDER BY fecha_pago ASC";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario]);
$pagos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi historial de pagos</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>Historial de mis pagos</h2>

  <table border="1" cellpadding="8">
    <tr>
      <th>Fecha de pago</th>
      <th>Mes</th>
      <th>Concepto</th>
      <th>Monto</th>
      <th>Recargo</th>
      <th>Verificado</th>
      <th>Acuse</th>
    </tr>

    <?php foreach ($pagos as $p): ?>
    <tr>
      <td><?= $p['fecha_pago'] ?></td>
      <td><?= $p['mes_correspondiente'] ?></td>
      <td><?= htmlspecialchars($p['concepto']) ?></td>
      <td>$<?= number_format($p['monto'], 2) ?></td>
      <td>$<?= number_format($p['recargo'], 2) ?></td>
      <td><?= $p['verificado'] ? "✅" : "❌" ?></td>
      <td>
        <?php if ($p['verificado']): ?>
          <a href="../php/pagos/generar_recibo.php?id_pago=<?= $p['id_pago'] ?>" target="_blank">Ver PDF</a>
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
