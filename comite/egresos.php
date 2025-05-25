<?php if (isset($_GET['registro']) && $_GET['registro'] === 'ok'): ?>
  <p style="color:green;"><strong>✅ Egreso registrado correctamente.</strong></p>
<?php endif; ?>

<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

$subrol = ucfirst($_SESSION['usuario']['subrol']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar egreso</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<h2>💸 Registrar egreso</h2>
<p><strong>Registrado por:</strong> <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?> (<?= $subrol ?>)</p>

<form action="../php/egresos/registrar.php" method="POST">
  <label>Monto:</label><br>
  <input type="number" name="monto" step="0.01" min="1" required><br><br>

  <label>Fecha del egreso:</label><br>
  <input type="date" name="fecha" value="<?= date('Y-m-d') ?>" required><br><br>

  <label>Motivo del egreso:</label><br>
  <textarea name="motivo" rows="4" cols="50" required></textarea><br><br>

  <label>Proveedor / Persona a quien se pagó:</label><br>
  <input type="text" name="proveedor" maxlength="100" required><br><br>

  <button type="submit">Registrar egreso</button>
</form>

<p><a href="historial_egresos.php">Historial de egresos</a></p>

<p><a href="dashboard.php">← Volver al panel</a></p>

</body>
</html>
