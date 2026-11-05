-- EJECUTAR DESPUES de BD_Furry.sql (sin modificarlo), en phpMyAdmin, pestaña SQL.
-- Este archivo NO cambia ninguna tabla: usa las que ya existen en BD_Furry.sql
-- (Imagenes_productos, Pedidos y Detalle_pedido). Se puede volver a ejecutar sin problema.
USE tienda_online;

-- 1. Fotos para los 3 productos que ya existian (archivos de la carpeta Img)
UPDATE Imagenes_productos SET url = 'zorra.jpg' WHERE id_producto = 1;
UPDATE Imagenes_productos SET url = 'Lobo.jpg'  WHERE id_producto = 2;
UPDATE Imagenes_productos SET url = 'gato.jpg'  WHERE id_producto = 3;

-- 2. Usuario de prueba (correo: prueba@furry.com  /  clave: 1234)
--    La clave queda en texto plano solo hasta su primer login: ahi PHP la convierte a hash.
INSERT INTO Usuarios (Nombre, Correo, Contraseña, Telefono, Fecha_registro, id_rol)
SELECT 'Usuario Prueba', 'prueba@furry.com', '1234', '', CURDATE(), 2 FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM Usuarios WHERE Correo = 'prueba@furry.com');

-- 3. Procedimientos almacenados (el DROP permite volver a ejecutar el archivo)
DROP PROCEDURE IF EXISTS sp_validar_usuario;
DROP PROCEDURE IF EXISTS sp_obtener_usuario;
DROP PROCEDURE IF EXISTS sp_actualizar_clave;
DROP PROCEDURE IF EXISTS sp_registrar_usuario;
DROP PROCEDURE IF EXISTS sp_insertar_producto;
DROP PROCEDURE IF EXISTS sp_listar_productos;
DROP PROCEDURE IF EXISTS sp_reclamar_producto;
DROP PROCEDURE IF EXISTS sp_mis_productos;

DELIMITER $$

-- Login: devuelve el usuario por correo (PHP compara la clave con password_verify)
CREATE PROCEDURE sp_obtener_usuario(IN p_correo VARCHAR(150))
BEGIN
  SELECT id_usuario, Nombre, Contraseña AS clave
  FROM Usuarios
  WHERE Correo = p_correo
  LIMIT 1;
END $$

-- Guarda la clave ya convertida a hash
CREATE PROCEDURE sp_actualizar_clave(IN p_id INT, IN p_clave VARCHAR(255))
BEGIN
  UPDATE Usuarios SET Contraseña = p_clave WHERE id_usuario = p_id;
END $$

-- Registro (si el correo ya existe, no inserta nada). Devuelve creado = 1 o 0
CREATE PROCEDURE sp_registrar_usuario(IN p_nombre VARCHAR(100), IN p_correo VARCHAR(150), IN p_pass VARCHAR(255))
BEGIN
  INSERT INTO Usuarios (Nombre, Correo, Contraseña, Fecha_registro, id_rol)
  SELECT p_nombre, p_correo, p_pass, CURDATE(), 2 FROM DUAL
  WHERE NOT EXISTS (SELECT 1 FROM Usuarios WHERE Correo = p_correo);

  SELECT ROW_COUNT() AS creado;
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

-- Reclamar: resta del stock general (solo si alcanza) Y deja el producto guardado
-- a nombre del usuario en Pedidos + Detalle_pedido. Todo o nada (transaccion).
-- Devuelve ok = 1 si se pudo, ok = 0 si no.
CREATE PROCEDURE sp_reclamar_producto(IN p_usuario INT, IN p_id INT, IN p_cantidad INT)
BEGIN
  DECLARE v_stock  INT;
  DECLARE v_precio DECIMAL(10,2);
  DECLARE v_tipo   VARCHAR(100);
  DECLARE v_pedido INT;
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    SELECT 0 AS ok;
  END;

  START TRANSACTION;

  SELECT p.stock_general, p.Precio_actual, c.nombre
    INTO v_stock, v_precio, v_tipo
  FROM Productos p
  LEFT JOIN Categorias c ON c.id_categoria = p.id_categoria
  WHERE p.id_producto = p_id
  FOR UPDATE;

  IF v_stock IS NOT NULL AND p_cantidad > 0 AND v_stock >= p_cantidad THEN
    UPDATE Productos SET stock_general = stock_general - p_cantidad WHERE id_producto = p_id;

    INSERT INTO Pedidos (id_usuario, Fecha_pedido, Estado, Total)
    VALUES (p_usuario, CURDATE(), 'Reclamado', v_precio * p_cantidad);
    SET v_pedido = LAST_INSERT_ID();

    INSERT INTO Detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario, tipo_producto)
    VALUES (v_pedido, p_id, p_cantidad, v_precio, v_tipo);

    COMMIT;
    SELECT 1 AS ok;
  ELSE
    ROLLBACK;
    SELECT 0 AS ok;
  END IF;
END $$

-- Lo que reclamo un usuario (para la pagina mis_productos.php)
CREATE PROCEDURE sp_mis_productos(IN p_usuario INT)
BEGIN
  SELECT pe.id_pedido, pe.Fecha_pedido, pr.nombre, d.cantidad, d.precio_unitario,
         d.cantidad * d.precio_unitario AS subtotal,
         (SELECT i.url FROM Imagenes_productos i WHERE i.id_producto = pr.id_producto LIMIT 1) AS imagen
  FROM Pedidos pe
  JOIN Detalle_pedido d ON d.id_pedido = pe.id_pedido
  JOIN Productos pr     ON pr.id_producto = d.id_producto
  WHERE pe.id_usuario = p_usuario
  ORDER BY pe.id_pedido DESC;
END $$

DELIMITER ;
