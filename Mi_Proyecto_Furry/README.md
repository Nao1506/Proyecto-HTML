# Tienda furry (PHP + MySQL)

## Cómo probarlo (XAMPP / Apache en localhost)
1. Copiá la carpeta del proyecto dentro de `htdocs` (XAMPP).
2. En phpMyAdmin → pestaña SQL: ejecutá `BD_Furry.sql` y después `BD_Furry_procedimientos.sql`
   (si ya habías ejecutado la versión anterior, volvé a ejecutar solo el de procedimientos: se puede repetir sin problema).
3. Abrí `http://localhost/Mi_Proyecto_Furry/index.php`
4. Usuario de prueba: `prueba@furry.com` / `1234` (o creá uno en "Registrate").

## Cómo funciona
- **Sesión**: `php/sesion.php` la inicia (dura 7 días en el navegador). Todas las páginas lo incluyen,
  por eso el menú (`php/nav.php`) y el index muestran tu nombre y "Cerrar sesión" cuando estás logueado.
- **Login**: login.php → php/logica_login.php (verifica con `password_verify`; las claves viejas en texto plano se convierten a hash solas en el primer ingreso).
- **Registro**: registro.php → php/logica_registro.php (guarda la clave con hash).
- **Panel** (privado): principal.php → php/guardar.php (sube un producto con foto a `Img/`).
- **Tienda** (pública): tienda.php (usa php/logica_catalogo.php). Para reclamar hay que estar logueado.
- **Reclamar**: php/logica_reclamar.php → `sp_reclamar_producto` resta del stock general y guarda lo reclamado
  a nombre del usuario en las tablas `Pedidos` y `Detalle_pedido` (transacción: o se hace todo o nada).
- **Mis productos** (privado): mis_productos.php lista lo que reclamaste.

## Archivos auxiliares en /php
- conexion.php (conexión + funciones `esc()`, `llamar()` y `url_imagen()`), sesion.php, auth.php (guardián), nav.php (menú), logout.php

## Imágenes de productos
- La foto se guarda en `Img/` y su nombre queda en `Imagenes_productos.url`. `BD_Furry.sql` no se modificó.
- Si el producto no tiene foto, la tienda muestra un 🐾.
