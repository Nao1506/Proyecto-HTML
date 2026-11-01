-- EJECUTAR DESPUES de BD_Furry.sql (sin modificarlo), en phpMyAdmin, pestaña SQL.
-- Este archivo NO cambia ninguna tabla: usa Imagenes_productos tal como esta en BD_Furry.sql.
USE tienda_online;

-- 1. Fotos para los 3 productos que ya existian (archivos de la carpeta Img)
UPDATE Imagenes_productos SET url = 'zorra.jpg' WHERE id_producto = 1;
UPDATE Imagenes_productos SET url = 'Lobo.jpg'  WHERE id_producto = 2;
UPDATE Imagenes_productos SET url = 'gato.jpg'  WHERE id_producto = 3;

-- 2. Usuario de prueba (correo: prueba@furry.com  /  clave: 1234)
INSERT INTO Usuarios (Nombre, Correo, Contraseña, Telefono, Fecha_registro, id_rol)
SELECT 'Usuario Prueba', 'prueba@furry.com', '1234', '', CURDATE(), 2 FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM Usuarios WHERE Correo = 'prueba@furry.com');

-- 3. Procedimientos almacenados (el DROP permite volver a ejecutar el archivo)
DROP PROCEDURE IF EXISTS sp_validar_usuario;
DROP PROCEDURE IF EXISTS sp_registrar_usuario;
DROP PROCEDURE IF EXISTS sp_insertar_producto;
DROP PROCEDURE IF EXISTS sp_listar_productos;
DROP PROCEDURE IF EXISTS sp_reclamar_producto;

DELIMITER $$

-- Login
CREATE PROCEDURE sp_validar_usuario(IN p_user VARCHAR(150), IN p_pass VARCHAR(255))
BEGIN
  SELECT id_usuario, Nombre FROM Usuarios
  WHERE Correo = p_user AND Contraseña = p_pass;
END $$

-- Registro (si el correo ya existe, no inserta nada)
CREATE PROCEDURE sp_registrar_usuario(IN p_nombre VARCHAR(100), IN p_correo VARCHAR(150), IN p_pass VARCHAR(255))
BEGIN
  INSERT INTO Usuarios (Nombre, Correo, Contraseña, Fecha_registro, id_rol)
  SELECT p_nombre, p_correo, p_pass, CURDATE(), 2 FROM DUAL
  WHERE NOT EXISTS (SELECT 1 FROM Usuarios WHERE Correo = p_correo);
END $$

-- Subir un producto: guarda el producto y despues su foto en Imagenes_productos
CREATE PROCEDURE sp_insertar_producto(IN p_categoria INT, IN p_nombre VARCHAR(150), IN p_desc VARCHAR(100),
                                      IN p_stock INT, IN p_precio DECIMAL(10,2), IN p_imagen VARCHAR(500))
BEGIN
  INSERT INTO Productos (id_categoria, nombre, descripcion, stock_general, Precio_actual)
  VALUES (p_categoria, p_nombre, p_desc, p_stock, p_precio);

  IF p_imagen <> '' THEN
    INSERT INTO Imagenes_productos (id_producto, url) VALUES (LAST_INSERT_ID(), p_imagen);
  END IF;
END $$

-- Listar productos con stock (con la primera foto de cada uno)
CREATE PROCEDURE sp_listar_productos()
BEGIN
  SELECT p.id_producto, p.nombre, p.descripcion, p.stock_general, p.Precio_actual,
         c.nombre AS categoria,
         (SELECT i.url FROM Imagenes_productos i WHERE i.id_producto = p.id_producto LIMIT 1) AS imagen
  FROM Productos p
  LEFT JOIN Categorias c ON p.id_categoria = c.id_categoria
  WHERE p.stock_general > 0
  ORDER BY p.id_producto DESC;
END $$

-- Reclamar: resta del stock general (solo si alcanza)
CREATE PROCEDURE sp_reclamar_producto(IN p_id INT, IN p_cantidad INT)
BEGIN
  UPDATE Productos SET stock_general = stock_general - p_cantidad
  WHERE id_producto = p_id AND stock_general >= p_cantidad;
END $$

DELIMITER ;
