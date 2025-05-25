<?php
require_once '../includes/auth.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitar servicio</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>Solicitud de servicio al comité</h2>

  <form action="../php/solicitudes/crear.php" method="POST">
    <label for="mensaje">Describe tu solicitud:</label><br>
    <textarea name="mensaje" rows="5" cols="50" placeholder="Ej. Hay una fuga en el jardín común..." required></textarea><br><br>

    <button type="submit">Enviar solicitud</button>
  </form>

  <p><a href="dashboard.php">← Volver al panel</a></p>
</body>
</html>
