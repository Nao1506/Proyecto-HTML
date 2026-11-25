# Tienda furry (PHP + MySQL)

## Cómo probarlo (XAMPP / Apache en localhost)
1. Copiá la carpeta `Mi_Proyecto_Furry` dentro de `htdocs` (XAMPP).
2. En phpMyAdmin → pestaña SQL:
   - **Base nueva:** ejecutá `BD_Furry.sql` y después `BD_Furry_procedimientos.sql`.
   - **Ya tenías la base de la versión anterior:** ejecutá `BD_Furry_actualizar_existente.sql` (una sola vez) y después `BD_Furry_procedimientos.sql`.
3. Abrí `http://localhost/Mi_Proyecto_Furry/index.php`

## Usuarios de prueba
| Tipo | Correo | Contraseña |
|------|--------|------------|
| Usuario | `prueba@furry.com` | `1234` |
| Administrador | `admin@furry.com` | `admin1234` |

También podés crear usuarios nuevos en "Registrate" (siempre se crean como usuarios comunes).

## Cómo funciona
- Al iniciar sesión se guarda al usuario en `$_SESSION` y **sigue logueado en todas las páginas**. El menú muestra "Hola, nombre" y el botón **Cerrar sesión**.
- Después de iniciar sesión te lleva al **Inicio** (ya no al formulario de subir producto).
- **Tienda** (`tienda.php`): todos pueden ver los productos. Con sesión iniciada aparece el botón "Publicar un producto" y "Agregar al carrito".
- **Carrito** (`carrito.php`): guardado en la tabla `Carrito` de la base. Se pueden quitar productos y "Finalizar compra", que crea el pedido (`Pedidos` + `Detalle_pedido`), resta el stock y vacía el carrito.
- **Administrador** (`es_admin = 1` en la tabla `Usuarios`):
  - `admin.php`: lista de usuarios y botón para **eliminar usuarios** (con confirmación). A los administradores no se los puede eliminar.
  - En la tienda ve el botón **Borrar producto**, que deja el stock en 0 (el producto sale de la tienda y de los carritos, pero los pedidos viejos no se rompen).

## Cambios en la base de datos
- `Usuarios.es_admin` (TINYINT, 0 = usuario, 1 = administrador).
- Tabla nueva `Carrito` (id_usuario, id_producto, cantidad).
- Procedimientos nuevos en `BD_Furry_procedimientos.sql`: `sp_agregar_carrito`, `sp_ver_carrito`, `sp_contar_carrito`, `sp_quitar_del_carrito`, `sp_finalizar_compra`, `sp_listar_usuarios`, `sp_eliminar_usuario`, `sp_borrar_producto`. `sp_validar_usuario` ahora también devuelve `es_admin`. Se eliminó `sp_reclamar_producto` (lo reemplaza el carrito).

## Archivos
- `index.php`, `login.php`, `registro.php`, `tienda.php`, `carrito.php`, `admin.php` → páginas.
- `php/sesion.php` → arranca la sesión en todas las páginas y deja `$logueado` / `$es_admin`.
- `php/auth.php` (exige login) y `php/auth_admin.php` (exige ser admin).
- `php/cabecera.php` y `php/pie.php` → menú y pie compartidos.
- `php/logica_login.php`, `logica_registro.php`, `logout.php`, `guardar.php` (subir producto), `logica_carrito.php`, `logica_admin.php`.
- `php/conexion.php` → conexión + función `consulta()` (consultas con parámetros seguros).

## Imágenes de productos
- La foto se guarda en la carpeta `Img/` y su nombre queda en `Imagenes_productos.url`. Sin foto, la tienda muestra un 🐾.
