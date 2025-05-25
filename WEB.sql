CREATE DATABASE proyectoWEB;
USE proyectoWEB;


-- Tabla de usuarios (comité e inquilinos)
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    rol ENUM('comite', 'inquilino') NOT NULL,
    subrol ENUM('presidente', 'secretario', 'vocal') DEFAULT NULL,
    telefono VARCHAR(20)
);

-- Tabla de casas (máximo 60 registros, se ingresarán manualmente del 1 al 60)
CREATE TABLE casas (
    id_casa INT PRIMARY KEY,  -- Del 1 al 60, ingresado manualmente
    numero_casa VARCHAR(10) NOT NULL UNIQUE,  -- Ej. "C-12"
    inquilino_id INT,  -- Usuario que ocupa la casa
    estatus ENUM('Disponible', 'Ocupada', 'Mantenimiento') NOT NULL DEFAULT 'Disponible',
    fecha_registro DATE NOT NULL,
    descripcion TEXT,
    FOREIGN KEY (inquilino_id) REFERENCES usuarios(id_usuario)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabla de pagos de mantenimiento
CREATE TABLE pagos (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_pago DATE NOT NULL,
    concepto VARCHAR(100),
    mes_correspondiente VARCHAR(20) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    recargo DECIMAL(10,2) DEFAULT 0,
    verificado BOOLEAN DEFAULT FALSE,
    fecha_verificacion DATE,
    id_verificador INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
         ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_verificador) REFERENCES usuarios(id_usuario)
         ON DELETE SET NULL ON UPDATE CASCADE
);

-- Tabla de egresos del comité
CREATE TABLE egresos (
    id_egreso INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    motivo TEXT NOT NULL,
    proveedor VARCHAR(100),
    id_responsable INT NOT NULL,
    FOREIGN KEY (id_responsable) REFERENCES usuarios(id_usuario)
         ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tabla de reservaciones de espacios comunes
CREATE TABLE reservaciones (
    id_reservacion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    recurso ENUM('palapa', 'alberca') NOT NULL,
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME NOT NULL,
    estatus ENUM('pendiente', 'aprobada', 'rechazada') DEFAULT 'pendiente',
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
         ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de solicitudes de servicio (foro)
CREATE TABLE solicitudes (
    id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    mensaje TEXT NOT NULL,
    fecha DATETIME NOT NULL,  -- Se usa DATETIME para incluir hora y fecha
    estatus ENUM('pendiente', 'en proceso', 'resuelto') DEFAULT 'pendiente',
    comentario_comite TEXT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
         ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de acuses de recibo (referencia a pago, PDF se genera en tiempo real)
CREATE TABLE acuse_recibo (
    id_acuse INT AUTO_INCREMENT PRIMARY KEY,
    id_pago INT NOT NULL,
    fecha_envio DATETIME NOT NULL,
    enviado_por INT NOT NULL,
    FOREIGN KEY (id_pago) REFERENCES pagos(id_pago)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (enviado_por) REFERENCES usuarios(id_usuario)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

--  Tabla de mensajes del foro
CREATE TABLE chat_foro (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    mensaje TEXT NOT NULL,
    fecha DATETIME NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE ON UPDATE CASCADE
);


-- SET time_zone = '-06:00';

USE proyectoWEB;
show tables;
 
select * from egresos;
select * from solicitudes;
select * from usuarios;
select * from pagos;
select * from casas;
delete from reservaciones where id_usuario = 5;
select * from reservaciones;
UPDATE usuarios
SET nombre = 'Diamante'  -- o el rol correcto
WHERE id_usuario = 5;

SELECT * FROM pagos ORDER BY id_pago DESC;
SELECT * FROM chat_foro;
DELETE FROM chat_foro WHERE id_mensaje > 0 AND id_usuario > 0 AND fecha > '2025-05-03 13:25:27';

SELECT * FROM usuarios;
SELECT * FROM reservaciones;

UPDATE casas
SET inquilino_id = 5
WHERE id_casa = 10;


ALTER TABLE solicitudes 
MODIFY estatus ENUM('pendiente', 'en proceso', 'resuelto', 'rechazada') 
NOT NULL DEFAULT 'pendiente';
SELECT * FROM casas;
select * from pagos;
INSERT INTO casas (
    id_casa,
    numero_casa,
    inquilino_id,
    estatus,
    fecha_registro,
    descripcion
) VALUES (
    12,
    'C-12',
    8,
    'Ocupada',
    CURDATE(),
    'Casa de prueba asignada al inquilino con ID 8 para validación del sistema.'
);

 UPDATE casas
 SET descripcion = 'Casa disponible por prueba de eliminacion de usuario'
 WHERE id_casa = '12'