<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

// Obtener casas ocupadas con nombre del inquilino
$sql = "SELECT c.id_casa, c.numero_casa, c.descripcion, u.nombre AS inquilino, u.id_usuario
        FROM casas c
        INNER JOIN usuarios u ON c.inquilino_id = u.id_usuario
        WHERE c.estatus = 'Ocupada'";
$stmt = $conn->query($sql);
$casasOcupadas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Liberar casas ocupadas</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<h2>🏠 Casas ocupadas</h2>

<?php if (count($casasOcupadas) > 0): ?>
  <table border="1" cellpadding="6">
    <tr>
      <th>Número de casa</th>
      <th>Inquilino</th>
      <th>Descripción</th>
      <th>Acción</th>
    </tr>

    <?php foreach ($casasOcupadas as $c): ?>
      <tr>
        <td><?= $c['numero_casa'] ?></td>
        <td><?= htmlspecialchars($c['inquilino']) ?></td>
        <td><?= nl2br(htmlspecialchars($c['descripcion'])) ?></td>
        <td>
          <form action="../php/casas/liberar.php" method="POST" onsubmit="return confirm('¿Estás seguro de desvincular a este inquilino de la casa?');">
            <input type="hidden" name="id_casa" value="<?= $c['id_casa'] ?>">
            <input type="hidden" name="id_usuario" value="<?= $c['id_usuario'] ?>">
            <button type="submit">Liberar casa</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php else: ?>
  <p>No hay casas ocupadas en este momento.</p>
<?php endif; ?>

<p><a href="dashboard.php">← Volver al panel del comité</a></p>

</body>
</html>
