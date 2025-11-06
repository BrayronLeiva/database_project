# Book Manager (database_project)

Pequeña aplicación web en PHP para gestionar una colección de libros usando SQLite.

## Qué hace

- CRUD básico de libros (crear, leer, actualizar, eliminar).
- Búsqueda por título o autor.
- Paginación en la lista principal.
- Validación en cliente (JavaScript) y en servidor (PHP).
- Instalador automático que crea la base de datos SQLite, tablas y datos de ejemplo.

## Requisitos

- PHP 7.4 o superior (el código usa propiedades tipadas y funciones modernas).
- Extensión PDO y PDO_SQLITE habilitadas.
- Un navegador web para la interfaz.

## Instalación y ejecución (rápido)

1. Descarga o clona el directorio del proyecto en tu máquina. Asegúrate de que la carpeta se llame `database_project` o ajusta el siguiente comando al nombre que uses.

2. Abre PowerShell (o tu terminal) y sitúate en la carpeta que contiene la carpeta `database_project`.

3. Levanta el servidor de desarrollo embebido de PHP con este comando:

```powershell
php -S localhost:8000 -t database_project
```

4. Abre en tu navegador: http://localhost:8000/ — la aplicación detectará si falta la base de datos y ejecutará el instalador (`install.php`) que crea `data/database.sqlite`, las tablas y datos de ejemplo.

Nota: si prefieres, también puedes entrar en `database_project` y ejecutar `php -S localhost:8000`.

## Credenciales por defecto

- Usuario admin: `admin`
- Contraseña: `clave123`

Estas credenciales se crean automáticamente por `config/setup.php` durante la instalación. Cámbialas en un entorno real.

## Estructura importante

- `index.php` — lista y búsqueda de libros.
- `add.php` — formulario para agregar libros (validación cliente/servidor).
- `edit.php` — formulario para editar libros.
- `delete.php` — elimina un libro (confirmación desde modal en la UI).
- `install.php` — script que crea `data/` y la base de datos si faltan.
- `config/database.php` — conexión PDO a la base de datos SQLite.
- `config/setup.php` — funciones para crear tablas y sembrar datos.
- `models/Book.php` y `models/User.php` — lógica de acceso a datos.

## Reset (borrar datos ejemplo)

Si quieres reiniciar la base de datos (eliminar datos y forzar re-instalación), borra el archivo:

```
database_project/data/database.sqlite
```

y vuelve a abrir `http://localhost:8000/` para que `install.php` lo recree.

## Seguridad y notas

- El proyecto usa sentencias preparadas para prevenir inyección SQL y `htmlspecialchars()` para escapar salidas.
- `htmlspecialchars()` puede recibir valores nulos en algunas plantillas; si actualizas a PHP 8.1+ podrían aparecer avisos deprecados. Se recomienda asegurarse de pasar siempre cadenas (por ejemplo `htmlspecialchars($var ?? '')`).

## Tests

Se hicieron pruebas manuales para comprobar el correcto funcionamiento.

