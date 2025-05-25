<?php
require_once '../includes/auth.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Apartar espacio común</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>Apartar palapa o alberca</h2>

  <form action="../php/reservaciones/crear.php" method="POST">
    <label for="recurso">Recurso:</label><br>
    <select name="recurso" required>
      <option value="palapa">Palapa</option>
      <option value="alberca">Alberca</option>
    </select><br><br>

    <label for="fecha_inicio">Fecha y hora de inicio:</label><br>
    <input type="datetime-local" name="fecha_inicio" required><br><br>

    <label for="fecha_fin">Fecha y hora de fin:</label><br>
    <input type="datetime-local" name="fecha_fin" required><br><br>

    <button type="submit">Solicitar apartado</button>
  </form>

  <p><a href="reservaciones_activas.php">Reservaciones de los inquilinos activas</a></p>

  <p><a href="mis_reservaciones.php">Mis reservaciones</a></p>
  
  <p><a href="dashboard.php">← Volver al panel</a></p>
</body>
</html>
