-- EJECUTAR DESPUES de BD_Furry.sql (o de BD_Furry_actualizar_existente.sql), en phpMyAdmin, pestaña SQL.
-- Se puede volver a ejecutar las veces que quieras (los DROP borran los procedimientos viejos).
-- Los procedimientos almacenados sirven para que el PHP sea mas corto: la logica de la base queda aca.
USE tienda_online;

-- 1. Fotos para los 3 productos que ya existian (archivos de la carpeta Img)
UPDATE Imagenes_productos SET url = 'zorra.jpg' WHERE id_producto = 1;
UPDATE Imagenes_productos SET url = 'Lobo.jpg'  WHERE id_producto = 2;
UPDATE Imagenes_productos SET url = 'gato.jpg'  WHERE id_producto = 3;

-- 2. Usuario de prueba (correo: prueba@furry.com  /  clave: 1234)
INSERT INTO Usuarios (Nombre, Correo, Contraseña, Telefono, Fecha_registro, id_rol)
SELECT 'Usuario Prueba', 'prueba@furry.com', '1234', '', CURDATE(), 2 FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM Usuarios WHERE Correo = 'prueba@furry.com');

-- 3. Usuario ADMINISTRADOR (correo: admin@furry.com  /  clave: admin1234)
INSERT INTO Usuarios (Nombre, Correo, Contraseña, Telefono, Fecha_registro, id_rol, es_admin)
SELECT 'Administrador', 'admin@furry.com', 'admin1234', '', CURDATE(), 1, 1 FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM Usuarios WHERE Correo = 'admin@furry.com');

-- (por si el admin ya existia sin el campo marcado)
UPDATE Usuarios SET es_admin = 1, id_rol = 1 WHERE Correo = 'admin@furry.com';

-- 4. Procedimientos almacenados
DROP PROCEDURE IF EXISTS sp_validar_usuario;
DROP PROCEDURE IF EXISTS sp_registrar_usuario;
DROP PROCEDURE IF EXISTS sp_insertar_producto;
DROP PROCEDURE IF EXISTS sp_listar_productos;
DROP PROCEDURE IF EXISTS sp_reclamar_producto;      -- ya no se usa (lo reemplaza el carrito)
DROP PROCEDURE IF EXISTS sp_agregar_carrito;
DROP PROCEDURE IF EXISTS sp_ver_carrito;
DROP PROCEDURE IF EXISTS sp_contar_carrito;
DROP PROCEDURE IF EXISTS sp_quitar_del_carrito;
DROP PROCEDURE IF EXISTS sp_finalizar_compra;
DROP PROCEDURE IF EXISTS sp_listar_usuarios;
DROP PROCEDURE IF EXISTS sp_eliminar_usuario;
DROP PROCEDURE IF EXISTS sp_borrar_producto;

DELIMITER $$

-- Login: devuelve tambien si es administrador
CREATE PROCEDURE sp_validar_usuario(IN p_user VARCHAR(150), IN p_pass VARCHAR(255))
BEGIN
  SELECT id_usuario, Nombre, es_admin FROM Usuarios
  WHERE Correo = p_user AND Contraseña = p_pass;
END $$

-- Registro (si el correo ya existe no inserta nada). Devuelve creado = 1 o 0
CREATE PROCEDURE sp_registrar_usuario(IN p_nombre VARCHAR(100), IN p_correo VARCHAR(150), IN p_pass VARCHAR(255))
BEGIN
  INSERT INTO Usuarios (Nombre, Correo, Contraseña, Fecha_registro, id_rol, es_admin)
  SELECT p_nombre, p_correo, p_pass, CURDATE(), 2, 0 FROM DUAL
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

-- CARRITO: agregar (nunca deja pasar la cantidad del stock). Devuelve agregado = 1 o 0
CREATE PROCEDURE sp_agregar_carrito(IN p_usuario INT, IN p_producto INT, IN p_cantidad INT)
BEGIN
  DECLARE v_stock INT DEFAULT 0;
  DECLARE v_ok INT DEFAULT 0;

  SELECT IFNULL(stock_general, 0) INTO v_stock FROM Productos WHERE id_producto = p_producto;

  IF v_stock > 0 AND p_cantidad > 0 THEN
    IF EXISTS (SELECT 1 FROM Carrito WHERE id_usuario = p_usuario AND id_producto = p_producto) THEN
      UPDATE Carrito SET cantidad = LEAST(cantidad + p_cantidad, v_stock)
      WHERE id_usuario = p_usuario AND id_producto = p_producto;
    ELSE
      INSERT INTO Carrito (id_usuario, id_producto, cantidad)
      VALUES (p_usuario, p_producto, LEAST(p_cantidad, v_stock));
    END IF;
    SET v_ok = 1;
  END IF;

  SELECT v_ok AS agregado;
END $$

-- CARRITO: ver lo que tiene el usuario
CREATE PROCEDURE sp_ver_carrito(IN p_usuario INT)
BEGIN
  SELECT p.id_producto, p.nombre, p.Precio_actual, c.cantidad, p.stock_general,
         (SELECT i.url FROM Imagenes_productos i WHERE i.id_producto = p.id_producto LIMIT 1) AS imagen,
         (c.cantidad * p.Precio_actual) AS subtotal
  FROM Carrito c
  JOIN Productos p ON p.id_producto = c.id_producto
  WHERE c.id_usuario = p_usuario
  ORDER BY c.id_carrito;
END $$

-- CARRITO: cantidad total de unidades (para el numerito del menu)
CREATE PROCEDURE sp_contar_carrito(IN p_usuario INT)
BEGIN
  SELECT IFNULL(SUM(cantidad), 0) AS total FROM Carrito WHERE id_usuario = p_usuario;
END $$

-- CARRITO: quitar un producto
CREATE PROCEDURE sp_quitar_del_carrito(IN p_usuario INT, IN p_producto INT)
BEGIN
  DELETE FROM Carrito WHERE id_usuario = p_usuario AND id_producto = p_producto;
END $$

-- CARRITO: finalizar compra. Crea el pedido, su detalle, resta el stock y vacia el carrito.
-- Devuelve id_pedido (0 si el carrito esta vacio o falta stock de algun producto)
CREATE PROCEDURE sp_finalizar_compra(IN p_usuario INT)
BEGIN
  DECLARE v_pedido INT DEFAULT 0;
  DECLARE v_total DECIMAL(10,2) DEFAULT 0;

  IF EXISTS (SELECT 1 FROM Carrito WHERE id_usuario = p_usuario)
     AND NOT EXISTS (SELECT 1 FROM Carrito c
                     JOIN Productos p ON p.id_producto = c.id_producto
                     WHERE c.id_usuario = p_usuario
                       AND (p.stock_general IS NULL OR c.cantidad > p.stock_general)) THEN

    SELECT SUM(c.cantidad * p.Precio_actual) INTO v_total
    FROM Carrito c
    JOIN Productos p ON p.id_producto = c.id_producto
    WHERE c.id_usuario = p_usuario;

    START TRANSACTION;

    INSERT INTO Pedidos (id_usuario, Fecha_pedido, Estado, Total)
    VALUES (p_usuario, CURDATE(), 'Pendiente', v_total);
    SET v_pedido = LAST_INSERT_ID();

    INSERT INTO Detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario, tipo_producto)
    SELECT v_pedido, c.id_producto, c.cantidad, p.Precio_actual, cat.nombre
    FROM Carrito c
    JOIN Productos p ON p.id_producto = c.id_producto
    LEFT JOIN Categorias cat ON cat.id_categoria = p.id_categoria
    WHERE c.id_usuario = p_usuario;

    UPDATE Productos p
    JOIN Carrito c ON c.id_producto = p.id_producto
    SET p.stock_general = p.stock_general - c.cantidad
    WHERE c.id_usuario = p_usuario;

    DELETE FROM Carrito WHERE id_usuario = p_usuario;

    COMMIT;
  END IF;

  SELECT v_pedido AS id_pedido;
END $$

-- ADMIN: listar todos los usuarios
CREATE PROCEDURE sp_listar_usuarios()
BEGIN
  SELECT id_usuario, Nombre, Correo, Fecha_registro, es_admin
  FROM Usuarios
  ORDER BY id_usuario;
END $$

-- ADMIN: eliminar un usuario (y todo lo que dependa de el). Un administrador NO se puede eliminar.
-- Devuelve borrado = 1 o 0
CREATE PROCEDURE sp_eliminar_usuario(IN p_usuario INT)
BEGIN
  DECLARE v_borrado INT DEFAULT 0;

  IF EXISTS (SELECT 1 FROM Usuarios WHERE id_usuario = p_usuario AND es_admin = 0) THEN
    START TRANSACTION;

    DELETE FROM Carrito            WHERE id_usuario = p_usuario;
    DELETE FROM ArticulosDeseado   WHERE id_usuario = p_usuario;
    DELETE FROM Resenias           WHERE id_usuario = p_usuario;
    DELETE FROM Direcciones_Envios WHERE id_usuario = p_usuario;
    DELETE FROM Detalle_pedido     WHERE id_pedido IN (SELECT id_pedido FROM Pedidos WHERE id_usuario = p_usuario);
    DELETE FROM Pedidos            WHERE id_usuario = p_usuario;
    DELETE FROM Usuarios           WHERE id_usuario = p_usuario;

    COMMIT;
    SET v_borrado = 1;
  END IF;

  SELECT v_borrado AS borrado;
END $$

-- ADMIN: "borrar" un producto = dejarle el stock en 0 (asi deja de aparecer en la tienda
-- pero no se rompen los pedidos viejos). Tambien lo saca de todos los carritos.
CREATE PROCEDURE sp_borrar_producto(IN p_producto INT)
BEGIN
  UPDATE Productos SET stock_general = 0 WHERE id_producto = p_producto;
  DELETE FROM Carrito WHERE id_producto = p_producto;

  SELECT COUNT(*) AS borrado FROM Productos WHERE id_producto = p_producto;
END $$

DELIMITER ;
