<?php
// Variables requeridas (simuladas si se usa individualmente)
// $nombre_inquilino, $fecha_pago, $mes, $monto, $recargo, $fecha_verificacion, $verificador, $subrol, $id_pago

$total = $monto + $recargo;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; margin: 40px; }
    h2 { text-align: center; }
    .resumen { margin-top: 20px; border: 1px solid #ccc; padding: 15px; }
    .firma { margin-top: 50px; text-align: right; }
    .firma small { color: #555; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    td, th { border: 1px solid #ccc; padding: 8px; }
  </style>
</head>
<body>

<h2>🏡 Acuse de Recibo de Pago</h2>

<p>Se emite el presente acuse a favor de <strong><?= htmlspecialchars($nombre_inquilino) ?></strong>, como constancia de que ha realizado el siguiente pago:</p>

<div class="resumen">
  <table>
    <tr>
      <th>Fecha de pago</th>
      <td><?= $fecha_pago ?></td>
    </tr>
    <tr>
      <th>Mes correspondiente</th>
      <td><?= $mes ?></td>
    </tr>
    <tr>
      <th>Monto base</th>
      <td>$<?= number_format($monto, 2) ?></td>
    </tr>
    <tr>
      <th>Recargo</th>
      <td>$<?= number_format($recargo, 2) ?></td>
    </tr>
    <tr>
      <th><strong>Total pagado</strong></th>
      <td><strong>$<?= number_format($total, 2) ?></strong></td>
    </tr>
  </table>
</div>

<div class="firma">
  <p><strong>Verificado por:</strong> <?= htmlspecialchars($verificador) ?> (<?= ucfirst($subrol) ?>)</p>
  <p><small>Fecha de verificación: <?= $fecha_verificacion ?></small></p>
  <p><small>Número de recibo: #<?= $id_pago ?></small></p>
</div>

</body>
</html>
