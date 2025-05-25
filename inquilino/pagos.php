<?php
require_once '../includes/auth.php';
$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pago de mantenimiento</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
  <script>
    function calcularRecargo() {
      const fecha = new Date(document.getElementById("fecha_pago").value);
      const dia = fecha.getDate();

      const monto = 650;
      let recargo = 0;
      if (dia > 10) recargo = 50;

      document.getElementById("monto").value = monto;
      document.getElementById("recargo_monto").value = recargo;
      document.getElementById("total_mostrar").innerText = "$" + (monto + recargo).toFixed(2);
    }
  </script>
</head>
<body>
  <h2>Registrar pago de mantenimiento</h2>

  <p><strong>Fecha de pago:</strong> <?= date('Y-m-d') ?></p>

<form action="../php/pagos/registrar.php" method="POST">

  <label>Fecha de pago:</label><br>
  <input type="date" value="<?= date('Y-m-d') ?>" readonly disabled><br><br>

  <label>Concepto:</label><br>
  <input type="text" name="concepto" placeholder="Ej. Cuota mayo 2025" required><br><br>

  <label>Monto base:</label><br>
  <input type="number" name="monto" value="650" readonly><br><br>

  <p style="color:gray;">💡 Si realizas el pago después del día 10 del mes, se aplicará automáticamente un recargo de $50.</p>

  <button type="submit">Registrar pago</button>
</form>


  <p><a href="historial_pagos.php">Ver historial de pagos</a></p>
  
  <p><a href="dashboard.php">← Volver al panel</a></p>
</body>
</html>
