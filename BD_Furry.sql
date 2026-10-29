CREATE DATABASE tienda_online;
USE tienda_online;


CREATE TABLE Roles (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);


CREATE TABLE MetodoPago (
    id_MetodoPago INT AUTO_INCREMENT PRIMARY KEY,
    nombre_metodo VARCHAR(60) NOT NULL
);


CREATE TABLE Envios (
    id_envio INT AUTO_INCREMENT PRIMARY KEY,
    empresa_correo VARCHAR(100),
    Codigo_Seguimiento VARCHAR(100),
    Costo_envio DECIMAL(10,2)
);


CREATE TABLE Descuentos_eventos (
    id_descuento INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL,
    porcentaje_descuento DECIMAL(5,2),
    fecha_inicio DATE,
    fecha_fin DATE
);


CREATE TABLE Categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL
);


CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Correo VARCHAR(150) NOT NULL,
    Contraseña VARCHAR(255) NOT NULL,
    Telefono VARCHAR(30),
    Fecha_registro DATE,
    id_rol INT,
    FOREIGN KEY (id_rol) REFERENCES Roles(id_rol)
);


CREATE TABLE Direcciones_Envios (
    id_Direccion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    calle VARCHAR(150),
    numero VARCHAR(20),
    ciudad VARCHAR(100),
    codigoPostal VARCHAR(20),
    Provincia VARCHAR(100),
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);


CREATE TABLE Productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT,
    nombre VARCHAR(150) NOT NULL,
    descripcion VARCHAR(100),
    stock_general INT,
    Precio_actual DECIMAL(10,2) NOT NULL,
    tamaño VARCHAR(100),
    FOREIGN KEY (id_categoria) REFERENCES Categorias(id_categoria)
);


CREATE TABLE Imagenes_productos (
    id_Imagen INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    url VARCHAR(500) NOT NULL,
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto)
);


CREATE TABLE ArticulosDeseado (
    id_articulos INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    Fecha_agregado DATE,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto)
);


CREATE TABLE Resenias (
    id_resenia INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_producto INT NOT NULL,
    calificacion INT,
    comentario TEXT,
    fecha_resenia DATE,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto)
);


CREATE TABLE Locales (
    id_local INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(200)
);


CREATE TABLE Stock_Locales (
    id_stocklocal INT AUTO_INCREMENT PRIMARY KEY,
    id_local INT NOT NULL,
    id_producto INT NOT NULL,
    cant_local INT DEFAULT 0,
    FOREIGN KEY (id_local) REFERENCES Locales(id_local),
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto)
);


CREATE TABLE Pedidos (
    id_pedido INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_MetodoPago INT,
    id_envio INT,
    Fecha_pedido DATE,
    Estado VARCHAR(50),
    Total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_MetodoPago) REFERENCES MetodoPago(id_MetodoPago),
    FOREIGN KEY (id_envio) REFERENCES Envios(id_envio)
);


CREATE TABLE Detalle_pedido (
    id_detalle INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido INT NOT NULL,
    id_producto INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    tipo_producto VARCHAR(100),
    FOREIGN KEY (id_pedido) REFERENCES Pedidos(id_pedido),
    FOREIGN KEY (id_producto) REFERENCES Productos(id_producto)
);






INSERT INTO Roles (nombre) VALUES
('Administrador'),
('Cliente'),
('Vendedor');


INSERT INTO MetodoPago (nombre_metodo) VALUES
('Mercado Pago'),
('Tarjeta de crédito'),
('Transferencia bancaria');


INSERT INTO Envios
(empresa_correo, Codigo_Seguimiento, Costo_envio) VALUES
('Correo Argentino', 'FUR1001', 3500.00),
('Andreani', 'FUR1002', 4200.00),
('OCA', 'FUR1003', 3000.00);


INSERT INTO Descuentos_eventos
(codigo, porcentaje_descuento, fecha_inicio, fecha_fin) VALUES
('FURRY10', 10.00, '2026-01-01', '2026-12-31'),
('FURFEST15', 15.00, '2026-03-01', '2026-12-31'),
('PAW20', 20.00, '2026-05-01', '2026-11-30');


INSERT INTO Categorias (nombre) VALUES
('Accesorios furry'),
('Fursuits y disfraces'),
('Peluches y coleccionables');


INSERT INTO Usuarios
(Nombre, Correo, Contraseña, Telefono, Fecha_registro, id_rol) VALUES
('Luna García', 'luna@example.com', 'DEMO_NO_USAR_01', '1123456789', '2026-01-10', 2),
('Nico Fernández', 'nico@example.com', 'DEMO_NO_USAR_02', '1134567890', '2026-01-15', 2),
('Milo Rodríguez', 'milo@example.com', 'DEMO_NO_USAR_03', '1145678901', '2026-02-01', 2);


INSERT INTO Direcciones_Envios
(id_usuario, calle, numero, ciudad, codigoPostal, Provincia) VALUES
(1, 'Avenida Santa Fe', '1234', 'Buenos Aires', 'C1059', 'CABA'),
(2, 'Calle San Martín', '567', 'La Plata', '1900', 'Buenos Aires'),
(3, 'Avenida Colón', '890', 'Córdoba', '5000', 'Córdoba');


INSERT INTO Productos
(id_categoria, nombre, descripcion, stock_general, Precio_actual, tamaño) VALUES
(1, 'Orejas de zorro', 'Orejas de peluche', 25, 12000.00, 'Único'),
(2, 'Cola de lobo', 'Cola sintética suave', 10, 28000.00, 'Mediano'),
(3, 'Peluches furry', 'Peluches de animales', 30, 18000.00, '30 cm');


INSERT INTO Imagenes_productos (id_producto, url) VALUES
(1, 'https://ejemplo.com/furry/orejas-zorro.jpg'),
(2, 'https://ejemplo.com/furry/cola-lobo.jpg'),
(3, 'https://ejemplo.com/furry/peluche.jpg');


INSERT INTO ArticulosDeseado
(id_usuario, id_producto, Fecha_agregado) VALUES
(1, 2, '2026-03-01'),
(2, 3, '2026-03-02'),
(3, 1, '2026-03-03');


INSERT INTO Resenias
(id_usuario, id_producto, calificacion, comentario, fecha_resenia) VALUES
(1, 1, 5, 'Las orejas son suaves y hermosas.', '2026-03-10'),
(2, 2, 4, 'Muy buena calidad y terminación.', '2026-03-11'),
(3, 3, 5, 'El peluche es adorable.', '2026-03-12');


INSERT INTO Locales (nombre, direccion) VALUES
('Furry World Centro', 'Avenida Corrientes 1000, CABA'),
('Furry World Norte', 'Avenida Cabildo 2000, CABA'),
('Furry World Córdoba', 'Avenida Colón 500, Córdoba');


INSERT INTO Stock_Locales
(id_local, id_producto, cant_local) VALUES
(1, 1, 10),
(2, 2, 4),
(3, 3, 12);


INSERT INTO Pedidos
(id_usuario, id_MetodoPago, id_envio, Fecha_pedido, Estado, Total) VALUES
(1, 1, 1, '2026-03-15', 'Entregado', 12000.00),
(2, 2, 2, '2026-03-16', 'En preparación', 28000.00),
(3, 3, 3, '2026-03-17', 'Pendiente', 18000.00);


INSERT INTO Detalle_pedido
(id_pedido, id_producto, cantidad, precio_unitario, tipo_producto) VALUES
(1, 1, 1, 12000.00, 'Accesorios furry'),
(2, 2, 1, 28000.00, 'Fursuits y disfraces'),
(3, 3, 1, 18000.00, 'Peluches y coleccionables');