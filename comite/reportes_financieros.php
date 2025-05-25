<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

// Obtener valores del filtro
$año = $_GET['año'] ?? date('Y');
$mes = $_GET['mes'] ?? '';

// Rango de fechas
if ($mes) {
    $fecha_inicio = "$año-$mes-01";
    $fecha_fin = date("Y-m-t", strtotime($fecha_inicio));
    $titulo = "Reporte financiero de " . date("F Y", strtotime($fecha_inicio));
} else {
    $fecha_inicio = "$año-01-01";
    $fecha_fin = "$año-12-31";
    $titulo = "Reporte financiero del año $año";
}

// Ingresos (pagos verificados)
$sqlIngresos = "SELECT SUM(monto + recargo) AS total_ingresos
                FROM pagos
                WHERE verificado = 1
                AND fecha_pago BETWEEN ? AND ?";
$stmt = $conn->prepare($sqlIngresos);
$stmt->execute([$fecha_inicio, $fecha_fin]);
$ingresos = $stmt->fetchColumn() ?? 0;

// Egresos
$sqlEgresos = "SELECT SUM(monto) AS total_egresos
               FROM egresos
               WHERE fecha BETWEEN ? AND ?";
$stmt = $conn->prepare($sqlEgresos);
$stmt->execute([$fecha_inicio, $fecha_fin]);
$egresos = $stmt->fetchColumn() ?? 0;

// Balance
$balance = $ingresos - $egresos;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= $titulo ?></title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<h2>📋 <?= $titulo ?></h2>

<form method="GET" action="">
  <label>Año:
    <input type="number" name="año" min="2023" value="<?= $año ?>" required>
  </label>

  <label>Mes:
    <select name="mes">
      <option value="">-- Todo el año --</option>
      <?php for ($m = 1; $m <= 12; $m++): 
        $value = str_pad($m, 2, '0', STR_PAD_LEFT);
        $selected = ($mes === $value) ? 'selected' : '';
      ?>
        <option value="<?= $value ?>" <?= $selected ?>>
          <?= ucfirst(strftime('%B', strtotime("2023-$value-01"))) ?>
        </option>
      <?php endfor; ?>
    </select>
  </label>

  <button type="submit">Generar reporte</button>
</form>

<hr>

<hr>
<h3>💰 Ingresos (Pagos de mantenimiento)</h3>

<?php
// Obtener lista de pagos verificados
$sqlPagos = "SELECT p.*, u.nombre
             FROM pagos p
             INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
             WHERE p.verificado = 1 AND p.fecha_pago BETWEEN ? AND ?
             ORDER BY p.fecha_pago ASC";
$stmt = $conn->prepare($sqlPagos);
$stmt->execute([$fecha_inicio, $fecha_fin]);
$lista_pagos = $stmt->fetchAll();
?>

<?php if (count($lista_pagos) > 0): ?>
  <table border="1" cellpadding="6">
    <tr>
      <th>Fecha de pago</th>
      <th>Inquilino</th>
      <th>Mes correspondiente</th>
      <th>Monto</th>
      <th>Recargo</th>
      <th>Total</th>
    </tr>
    <?php foreach ($lista_pagos as $p): ?>
      <tr>
        <td><?= $p['fecha_pago'] ?></td>
        <td><?= htmlspecialchars($p['nombre']) ?></td>
        <td><?= $p['mes_correspondiente'] ?></td>
        <td>$<?= number_format($p['monto'], 2) ?></td>
        <td>$<?= number_format($p['recargo'], 2) ?></td>
        <td>
          <strong>$<?= number_format($p['monto'] + $p['recargo'], 2) ?></strong>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php else: ?>
  <p>No hay pagos registrados en este periodo.</p>
<?php endif; ?>

<hr>
<h3>💸 Egresos realizados por el comité</h3>

<?php
// Obtener lista de egresos
$sqlEgresos = "SELECT e.*, u.nombre, u.subrol
               FROM egresos e
               INNER JOIN usuarios u ON e.id_responsable = u.id_usuario
               WHERE e.fecha BETWEEN ? AND ?
               ORDER BY e.fecha ASC";
$stmt = $conn->prepare($sqlEgresos);
$stmt->execute([$fecha_inicio, $fecha_fin]);
$lista_egresos = $stmt->fetchAll();
?>

<?php if (count($lista_egresos) > 0): ?>
  <table border="1" cellpadding="6">
    <tr>
      <th>Fecha</th>
      <th>Proveedor</th>
      <th>Monto</th>
      <th>Motivo</th>
      <th>Responsable</th>
    </tr>
    <?php foreach ($lista_egresos as $e): ?>
      <tr>
        <td><?= $e['fecha'] ?></td>
        <td><?= htmlspecialchars($e['proveedor']) ?></td>
        <td>$<?= number_format($e['monto'], 2) ?></td>
        <td><?= nl2br(htmlspecialchars($e['motivo'])) ?></td>
        <td><?= htmlspecialchars($e['nombre']) ?> (<?= ucfirst($e['subrol']) ?>)</td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php else: ?>
  <p>No hay egresos registrados en este periodo.</p>
<?php endif; ?>

<hr>


<h3>Resumen financiero</h3>
<ul>
  <li>💰 Ingresos totales: <strong>$<?= number_format($ingresos, 2) ?></strong></li>
  <li>💸 Egresos totales: <strong>$<?= number_format($egresos, 2) ?></strong></li>
  <li>📊 Balance: 
    <strong style="color:<?= $balance >= 0 ? 'green' : 'red' ?>">
      $<?= number_format($balance, 2) ?>
    </strong>
  </li>
</ul>

<form method="GET" action="../php/reportes/exportar_excel.php">
  <input type="hidden" name="año" value="<?= $año ?>">
  <input type="hidden" name="mes" value="<?= $mes ?>">
  <button type="submit">📥 Exportar reporte en Excel (.csv)</button>
</form>

<p><a href="dashboard.php">← Volver al panel</a></p>

</body>
</html>
