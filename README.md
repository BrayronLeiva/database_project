# 📚 Book Manager Pro

## Sistema de Gestión de Biblioteca Auto-Instalable

Sistema web completo de gestión de biblioteca personal desarrollado con PHP y SQLite, que incluye un mecanismo de auto-instalación para facilitar su despliegue en diferentes entornos.

---

## ✨ Características Principales

- ✅ **Sistema CRUD Completo**: Crear, leer, actualizar y eliminar libros
- 🔧 **Auto-instalación**: Script automatizado de instalación y configuración
- 🔒 **Seguridad Robusta**: Prepared statements y hash de contraseñas
- 📱 **Interfaz Responsiva**: Diseño adaptable a diferentes dispositivos
- 💾 **SQLite**: Base de datos ligera y portable
- 👤 **Usuario Administrador**: Sistema con usuario por defecto preconfigurado
- 📊 **Datos de Ejemplo**: Incluye libros de muestra para pruebas

---

## 🛠️ Requisitos del Sistema

- **Servidor Web**: Apache/Nginx con PHP
- **PHP**: Versión 7.4 o superior
- **Extensiones PHP Requeridas**:
  - PDO
  - SQLite3
- **Permisos**: Escritura en el directorio del proyecto

---

## 📦 Estructura del Proyecto

```
book_manager/
├── index.php              # Página principal - lista de libros
├── install.php            # Script de auto-instalación
├── add.php                # Formulario para agregar libros
├── edit.php               # Formulario para editar libros
├── delete.php             # Eliminación de libros
├── config/
│   ├── database.php       # Conexión PDO (auto-generado)
│   └── setup.php          # Funciones de instalación
├── models/
│   ├── Book.php           # Modelo de gestión de libros
│   └── User.php           # Modelo de gestión de usuarios
├── data/
│   └── database.sqlite    # Base de datos (auto-creada)
├── assets/
│   └── css/
│       └── styles.css     # Estilos de la aplicación
└── README.md              # Este archivo
```

---

## 🚀 Instalación

### Opción 1: Instalación Automática (Recomendada)

1. **Descargar y Descomprimir**
   ```bash
   unzip book_manager_grupo_X.zip
   ```

2. **Subir al Servidor**
   - Subir la carpeta completa al servidor web
   - Asegurar permisos de escritura en el directorio

3. **Acceder desde el Navegador**
   ```
   http://tu-servidor/book_manager/
   ```

4. **Ejecutar Instalación**
   - El sistema detectará automáticamente que no está instalado
   - Será redirigido a `install.php`
   - La instalación se ejecutará automáticamente

5. **Redirección Automática**
   - Tras la instalación, será redirigido a la página principal
   - El sistema estará listo para usar

### Opción 2: Instalación Manual

Si necesitas reinstalar el sistema:

1. Eliminar el archivo `data/database.sqlite`
2. Eliminar el archivo `config/database.php`
3. Acceder nuevamente al proyecto desde el navegador

---

## 🔑 Credenciales por Defecto

Al completar la instalación, se crea automáticamente un usuario administrador:

- **Usuario**: `admin`
- **Contraseña**: `clave123`
- **Email**: `admin@biblioteca.edu`

> ⚠️ **Importante**: Se recomienda cambiar estas credenciales en producción.

---

## 📖 Uso del Sistema

### Listar Libros
- La página principal (`index.php`) muestra todos los libros registrados
- Incluye información de título, autor, año y género
- Paginación básica disponible

### Agregar Libro
1. Click en el botón "Agregar Libro"
2. Completar el formulario con:
   - Título (obligatorio)
   - Autor (obligatorio)
   - Año de publicación
   - Género
3. Click en "Guardar"

### Editar Libro
1. Click en el botón "Editar" del libro deseado
2. Modificar los campos necesarios
3. Click en "Actualizar"

### Eliminar Libro
1. Click en el botón "Eliminar"
2. Confirmar la acción en el diálogo JavaScript
3. El libro será eliminado permanentemente

---

## 🗄️ Estructura de Base de Datos

### Tabla: books
```sql
CREATE TABLE books (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    author TEXT NOT NULL,
    year INTEGER,
    genre TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### Tabla: users
```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    email TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🔒 Características de Seguridad

### Implementadas

- ✅ **Prepared Statements**: Todas las consultas SQL utilizan prepared statements para prevenir inyección SQL
- ✅ **Hash de Contraseñas**: Las contraseñas se almacenan usando `password_hash()` de PHP
- ✅ **Validación de Servidor**: Todos los formularios incluyen validación del lado del servidor
- ✅ **Sanitización de Salida**: Uso de `htmlspecialchars()` para prevenir XSS
- ✅ **Confirmación de Eliminación**: JavaScript solicita confirmación antes de eliminar registros

### Recomendaciones Adicionales

- Cambiar credenciales de administrador por defecto
- Implementar sistema de autenticación completo
- Configurar HTTPS en producción
- Establecer permisos restrictivos en archivos y directorios
- Realizar backups regulares de la base de datos

---

## 🧪 Pruebas

### Datos de Ejemplo

El sistema incluye libros de ejemplo automáticamente al instalarse:

1. "Cien años de soledad" - Gabriel García Márquez (1967)
2. "Don Quijote de la Mancha" - Miguel de Cervantes (1605)
3. "1984" - George Orwell (1949)

### Pruebas de Portabilidad

El sistema ha sido probado en:
- ✅ XAMPP (Windows)
- ✅ Apache (Linux/Ubuntu)
- ✅ Servidor de desarrollo PHP integrado

---

## 👥 Equipo de Desarrollo

### Roles y Responsabilidades

| Rol | Responsabilidades | Archivos |
|-----|------------------|----------|
| **Architect & DB Manager** | Diseño de BD, sistema de instalación | `install.php`, `config/setup.php` |
| **Backend Developer** | Lógica CRUD, seguridad | `models/Book.php`, `models/User.php` |
| **Frontend Developer** | Interfaz de usuario, formularios | `index.php`, `add.php`, `edit.php` |
| **QA & Deployment** | Testing, documentación | `README.md`, pruebas |

---

## 💻 Tecnologías Utilizadas

- **Lenguaje**: PHP 7.4+
- **Base de Datos**: SQLite 3
- **Acceso a Datos**: PDO (PHP Data Objects)
- **Frontend**: HTML5, CSS3, JavaScript
- **Seguridad**: Password Hashing, Prepared Statements
- **Control de Versiones**: Git

---

## 🐛 Solución de Problemas

### Error: "Cannot write to database"
**Solución**: Verificar permisos de escritura en el directorio `data/`
```bash
chmod -R 755 data/
```

### Error: "Database not found"
**Solución**: Ejecutar nuevamente el instalador accediendo a `install.php`

### Error: "PDO extension not loaded"
**Solución**: Habilitar la extensión PDO en `php.ini`
```ini
extension=pdo_sqlite
```

---

## 📝 Funcionalidades Extra (Bonus)

Las siguientes funcionalidades son opcionales y pueden implementarse:

- 🔍 Sistema de búsqueda por título/autor
- 📱 Diseño responsive mejorado
- 🏷️ Categorización avanzada de libros
- 📊 Reportes y estadísticas de biblioteca
- 👥 Sistema de login multi-usuario
- 📤 Exportación de datos a CSV/PDF

---

## 📄 Licencia

Este proyecto fue desarrollado con fines educativos como parte del Proyecto 02 - Base de Datos.

---

## 📞 Soporte

Para reportar problemas o sugerencias:

1. Verificar la sección de [Solución de Problemas](#-solución-de-problemas)
2. Revisar los logs en `php_errors.log`
3. Contactar al equipo de desarrollo

---

## 🎯 Objetivos de Aprendizaje Alcanzados

- ✅ Implementación de operaciones CRUD con PHP y SQLite
- ✅ Diseño de sistema de auto-instalación
- ✅ Aplicación de principios de seguridad web
- ✅ Gestión de conexiones con PDO
- ✅ Creación de interfaces funcionales y responsivas
- ✅ Trabajo colaborativo con roles específicos
- ✅ Documentación técnica completa

---

## 📅 Historial de Versiones

### v1.0.0 - Noviembre 2025
- ✨ Lanzamiento inicial
- ✅ Sistema CRUD completo
- ✅ Auto-instalación funcional
- ✅ Seguridad implementada
- ✅ Interfaz de usuario básica
- ✅ Documentación completa

---

**Desarrollado con 💻 y ☕ por el Grupo X**

*Proyecto 02 - Sistema de Gestión de Biblioteca Auto-Instalable*
