# Tienda furry (PHP + MySQL)

## Cómo probarlo (XAMPP / Apache en localhost)
1. Copiá la carpeta del proyecto dentro de `htdocs` (XAMPP) o de la carpeta pública de Apache.
2. En phpMyAdmin → pestaña SQL: ejecutá `BD_Furry.sql` y después `BD_Furry_procedimientos.sql`.
3. Abrí `http://localhost/Mi_Proyecto_Furry/index.html`
4. Usuario de prueba: `prueba@furry.com` / `1234` (o creá uno en "Registrate").

## Archivos
- login.html → php/logica_login.php  (crea la sesión)
- registro.html → php/logica_registro.php
- principal.php (privado): formulario para subir producto → php/guardar.php
- php/logica_catalogo.php → vista_catalogo.php (público): lista productos con formulario "Reclamar"
- php/logica_reclamar.php: resta del stock general
- php/auth.php (guardián), php/logout.php, php/conexion.php

## Imágenes de productos
- La foto se guarda en la carpeta `Img/` y su nombre queda en la tabla `Imagenes_productos` (columna `url`) de `BD_Furry.sql`, que no se modificó.
- Si el producto no tiene foto, la tienda muestra un 🐾.
