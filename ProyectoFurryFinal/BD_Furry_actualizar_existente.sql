-- SOLO si ya tenias la base "tienda_online" creada con la version anterior.
-- (Si la creas de cero con BD_Furry.sql, NO hace falta ejecutar este archivo.)
-- Ejecutalo UNA sola vez en phpMyAdmin (pestaña SQL) y despues ejecuta BD_Furry_procedimientos.sql
USE tienda_online;

-- 1. Campo para saber si el usuario es administrador (0 = no, 1 = si)
ALTER TABLE Usuarios ADD COLUMN es_admin TINYINT(1) NOT NULL DEFAULT 0;

-- 2. Tabla del carrito (un renglon por producto y por usuario)
CREATE TABLE IF NOT EXISTS Carrito (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    UNIQUE KEY uq_carrito (id_usuario, id_producto),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto)
);
