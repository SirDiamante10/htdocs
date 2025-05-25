<?php

session_start();

// Si hay un usuario en sesión
if (isset($_SESSION['usuario'])) {
    $rol = $_SESSION['usuario']['rol'];

    if ($rol === 'comite') {
        header("Location: comite/dashboard.php");
        exit();
    } elseif ($rol === 'inquilino') {
        header("Location: inquilino/dashboard.php");
        exit();
    }
}

// Si no hay sesión activa, mandar a login
header("Location: login.php");
exit();

?>