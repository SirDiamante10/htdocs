<?php
require_once '../includes/auth.php';
require_once '../config/conexion.php';

$id_usuario = $_SESSION['usuario']['id_usuario'];

// Obtener número actual
$sql = "SELECT telefono FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevoTelefono = trim($_POST['telefono']);

    // Validación básica (puedes usar expresiones regulares más adelante)
    if (!preg_match('/^[0-9\-\+\s]{7,20}$/', $nuevoTelefono)) {
        die("Formato de teléfono no válido.");
    }

    // Actualizar en la base de datos
    $sqlUpdate = "UPDATE usuarios SET telefono = ? WHERE id_usuario = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->execute([$nuevoTelefono, $id_usuario]);

    header("Location: mi_cuenta.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar teléfono</title>
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<h2>📞 Editar teléfono</h2>

<form method="POST" action="">
  <label for="telefono">Nuevo número:</label><br>
  <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required><br><br>
  <button type="submit">Guardar cambios</button>
</form>

<p><a href="mi_cuenta.php">← Volver a mi cuenta</a></p>

</body>
</html>
