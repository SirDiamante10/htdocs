<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

$usuario = $_SESSION['usuario'];

// Obtener todos los pagos pendientes de verificación
$sql = "SELECT p.id_pago, p.fecha_pago, p.mes_correspondiente, p.monto, p.recargo,
               u.nombre AS inquilino
        FROM pagos p
        INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
        WHERE p.verificado = 0
        ORDER BY p.fecha_pago ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$pagos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Verificar pagos</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>Pagos pendientes de verificación</h2>

  <?php if (count($pagos) > 0): ?>
    <table border="1" cellpadding="10">
      <thead>
        <tr>
          <th>Inquilino</th>
          <th>Mes</th>
          <th>Fecha de pago</th>
          <th>Monto</th>
          <th>Recargo</th>
          <th>Acción</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($pagos as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['inquilino']) ?></td>
            <td><?= $p['mes_correspondiente'] ?></td>
            <td><?= $p['fecha_pago'] ?></td>
            <td>$<?= number_format($p['monto'], 2) ?></td>
            <td>$<?= number_format($p['recargo'], 2) ?></td>
            <td>
            <form action="../php/pagos/verificar.php" method="POST">
                <input type="hidden" name="id_pago" value="<?= $p['id_pago'] ?>">
                <button type="submit">Verificar</button>
            </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No hay pagos pendientes por verificar.</p>
  <?php endif; ?>

  <p><a href="historial_pagos.php">Historial completo de pagos</a></p>
  
  <p><a href="dashboard.php">← Volver al panel</a></p>
</body>
</html>
