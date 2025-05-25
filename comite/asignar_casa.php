<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

// Obtener casas disponibles
$sql = "SELECT * FROM casas WHERE estatus = 'Disponible'";
$stmt = $conn->query($sql);
$casas = $stmt->fetchAll();

// Obtener lista de usuarios tipo inquilino sin casa asignada
$sqlInq = "SELECT u.id_usuario, u.nombre 
           FROM usuarios u
           WHERE u.rol = 'inquilino'
           AND u.id_usuario NOT IN (SELECT inquilino_id FROM casas WHERE inquilino_id IS NOT NULL)";
$stmtInq = $conn->query($sqlInq);
$inquilinos = $stmtInq->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Asignar casas</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<h2>🏠 Asignación de casas disponibles</h2>

<?php if (count($casas) > 0 && count($inquilinos) > 0): ?>
  <table border="1" cellpadding="6">
    <tr>
      <th>Número de casa</th>
      <th>Descripción</th>
      <th>Asignar a</th>
    </tr>

    <?php foreach ($casas as $casa): ?>
      <tr>
        <td><?= htmlspecialchars($casa['numero_casa']) ?></td>
        <td><?= nl2br(htmlspecialchars($casa['descripcion'])) ?></td>
        <td>
          <form action="../php/casas/asignar.php" method="POST">
            <input type="hidden" name="id_casa" value="<?= $casa['id_casa'] ?>">
            <select name="id_usuario" required>
              <option value="">-- Seleccionar inquilino --</option>
              <?php foreach ($inquilinos as $inq): ?>
                <option value="<?= $inq['id_usuario'] ?>"><?= htmlspecialchars($inq['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit">Asignar</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php elseif (count($casas) === 0): ?>
  <p>Todas las casas están ocupadas.</p>
<?php elseif (count($inquilinos) === 0): ?>
  <p>No hay inquilinos disponibles para asignar.</p>
<?php endif; ?>

<p><a href="dashboard.php">← Volver al panel del comité</a></p>

</body>
</html>
