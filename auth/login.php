<?php
session_start();
require_once '../../config/conexion.php';



$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Verificar que el correo exista
$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$email]);
$usuario = $stmt->fetch();

if ($usuario && password_verify($password, $usuario['contraseña'])) {
    // Guardar los datos del usuario en sesión
    $_SESSION['usuario'] = $usuario;

    // Redirigir según el rol
    if ($usuario['rol'] === 'comite') {
        header('Location: ../../comite/dashboard.php');
    } elseif ($usuario['rol'] === 'inquilino') {
        header('Location: ../../inquilino/dashboard.php');
    }
    exit();
} else {
    $_SESSION['error_login'] = "Correo o contraseña incorrectos.";
    header('Location: ../../login.php');
    exit();
}

?>
<?php if (isset($_GET['eliminado']) && $_GET['eliminado'] === 'ok'): ?>
    <p style="color:green;">Tu cuenta ha sido eliminada correctamente.</p>
  <?php endif; ?>
  