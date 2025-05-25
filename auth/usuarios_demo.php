<?php
require_once '../../config/conexion.php';

// Lista de usuarios de ejemplo
$usuarios = [
    [
        'nombre' => 'pruebaMalevola',
        'email' => 'muerto@gmail.com',
        'password' => 'a',
        'rol' => 'inquilino',
        'subrol' => null,
        'telefono' => '22'
    ],


];

foreach ($usuarios as $u) {
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contraseña, rol, subrol, telefono) VALUES (?, ?, ?, ?, ?, ?)");
    $hashed = password_hash($u['password'], PASSWORD_DEFAULT);
    $stmt->execute([$u['nombre'], $u['email'], $hashed, $u['rol'], $u['subrol'], $u['telefono']]);
}

echo "Usuarios de prueba creados.";
