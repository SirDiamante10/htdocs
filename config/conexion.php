<?php

// Configuración de zona horaria (CDMX)
date_default_timezone_set('America/Mexico_City');

// Configuración de conexión a la base de datos
$host = 'localhost';           // Dirección del servidor (usualmente localhost en desarrollo)
$dbname = 'proyectoWEB';        // Nombre de tu base de datos (ajústalo según tu configuración)
$usuario = 'root';             // Usuario de MySQL
$contrasena = 'DBAr00t++';              // Contraseña del usuario de MySQL
 
try {
    // Crear una instancia PDO para conectarse a la BD
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $contrasena);

    // Configurar el modo de errores para que lance excepciones si algo falla
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Opcional: puedes activar el modo fetch por defecto (traer resultados como arrays asociativos)
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Asegurar zona horaria también en MySQL (importante si usas funciones de fecha en SQL)
    $conn->exec("SET time_zone = '-06:00'");
    
} catch (PDOException $e) {
    // En caso de error, mostrar mensaje (en producción esto se debe ocultar)
    die("Error de conexión: " . $e->getMessage());
}
?>
