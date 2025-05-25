<?php
require_once '../includes/auth.php'; // Verifica sesión
$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel del Comité</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
  <h2>Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?> 👨‍💼</h2>
  <p>Rol: <?php echo $usuario['rol']; ?> - <?php echo $usuario['subrol']; ?></p>

  <ul>
    <li><a href="mi_cuenta.php">Cuenta</a></li>
    <li><a href="pagos.php">Gestionar pagos de mantenimiento</a></li>
    <li><a href="reservaciones.php">Gestionar reservaciones</a></li>
    <li><a href="solicitudes.php">Gestionar solicitudes de servicio</a></li>
    <li><a href="egresos.php">Gestionar egresos</a></li>
    <li><a href="reportes_financieros.php">Ver reportes financieros</a></li>
    <li><a href="../php/auth/logout.php">Cerrar sesión</a></li>
  </ul>
</body>
</html>
