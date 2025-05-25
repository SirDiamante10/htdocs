<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

$id_usuario = $_SESSION['usuario']['id_usuario'];

// Obtener datos del usuario
$sql = "SELECT u.nombre, u.email, u.telefono, u.rol,
               c.id_casa, c.numero_casa, c.estatus AS estatus_casa, c.descripcion
        FROM usuarios u
        LEFT JOIN casas c ON c.inquilino_id = u.id_usuario
        WHERE u.id_usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();

// Verificar si ha pagado este mes
$mesActual = date('F');  // Ej. "May"
$sqlPago = "SELECT * FROM pagos 
            WHERE id_usuario = ? AND mes_correspondiente = ? AND verificado = 1";
$stmtPago = $conn->prepare($sqlPago);
$stmtPago->execute([$id_usuario, $mesActual]);
$pagado = $stmtPago->rowCount() > 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi cuenta</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<?php if (isset($_GET['baja']) && $_GET['baja'] === 'ok'): ?>
  <p style="color:green;"><strong>✅ Has solicitado tu baja como inquilino. Tu casa ha sido liberada.</strong></p>
<?php endif; ?>


<h2>👤 Mi cuenta</h2>

<h3>Información personal</h3>
<p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>
<p><strong>Correo electrónico:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
<p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono']) ?> 
  <a href="editar_telefono.php">[Editar]</a></p>

<hr>

<h3>Información como inquilino</h3>

<?php if ($usuario['id_casa']): ?>
  <p><strong>Número de casa:</strong> <?= htmlspecialchars($usuario['numero_casa']) ?></p>
<?php else: ?>
  <p><strong>Número de casa: </strong><em>No estás vinculado a ninguna casa actualmente.</em></p>
<?php endif; ?>

<p><strong>Estado de pago del mes (<?= $mesActual ?>):</strong> 
  <?= $pagado ? '✅ Pagado' : '❌ No pagado' ?></p>


<hr>

<h3>Opciones de cuenta</h3>

<!-- Solicitar baja como inquilino -->
<form action="solicitar_baja.php" method="POST" onsubmit="return confirm('¿Deseas dejar de ser inquilino y liberar tu casa?');">
  <button type="submit">Solicitar baja como inquilino</button>
</form>
<br>

<!-- Eliminar cuenta -->
<form action="eliminar_cuenta.php" method="POST" onsubmit="return confirm('¿Estás completamente seguro de eliminar tu cuenta? Esta acción no se puede deshacer.');">
  <button type="submit" style="color:red;">Eliminar cuenta permanentemente</button>
</form>
<br>

<!-- Cerrar sesión -->
<form action="../php/auth/logout.php" method="POST">
  <button type="submit">Cerrar sesión</button>
</form>

<p><a href="dashboard.php">← Volver al panel</a></p>

</body>
</html>
