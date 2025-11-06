# 📚 Book Manager - Sistema de Gestión de Biblioteca

> Sistema web autoinstalable para gestión de biblioteca personal con interfaz moderna y funcionalidades CRUD completas.

![Version](https://img.shields.io/badge/version-1.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)
![SQLite](https://img.shields.io/badge/SQLite-3-green.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

---

## ✨ Características Principales

### 🎨 Frontend Moderno
- **Diseño Responsive**: Adaptable a diferentes tamaños de pantalla
- **Interfaz Intuitiva**: Navegación clara y sencilla
- **Iconos Emoji**: Interface amigable y visual
- **Validación en Tiempo Real**: Feedback inmediato en formularios

### 🔍 Funcionalidades Principales
- **Listar Libros**: Vista paginada de todos los libros (10 por página)
- **Agregar Libros**: Formulario con validación cliente y servidor
- **Editar Libros**: Modificar información de libros existentes
- **Eliminar Libros**: Eliminación con confirmación
- **Buscar Libros**: Filtrado por título o autor
- **Paginación**: Navegación entre páginas de resultados

### 🛡️ Seguridad
- **Prepared Statements**: Protección contra SQL Injection
- **Password Hashing**: Contraseñas seguras con bcrypt (usuario admin)
- **Validación de Datos**: Sanitización de entrada y salida
- **Escape de HTML**: Prevención de XSS
- **Confirmación de Eliminación**: Modal para evitar borrados accidentales

### 🚀 Auto-Instalación
- **Detección Automática**: Redirige a instalación si es necesario
- **Configuración Simple**: Un solo clic para instalar
- **Datos de Ejemplo**: Incluye 15 libros de muestra
- **Usuario Admin**: Creado automáticamente en la BD

---

## 📋 Requisitos del Sistema

- PHP 7.4 o superior
- Extensión PDO SQLite habilitada
- Servidor web (Apache, Nginx, o PHP built-in)
- Permisos de escritura en el directorio raíz del proyecto

---

## 🔧 Instalación

### Opción 1: Instalación Rápida (PHP Built-in)

1. **Descargar** el proyecto:
   ```bash
   unzip database_project.zip
   cd database_project
   ```

2. **Iniciar servidor**:
   ```bash
   php -S localhost:8000
   ```

3. **Abrir navegador**:
   ```
   http://localhost:8000
   ```

4. **La instalación ocurre automáticamente** la primera vez que accedes

### Opción 2: Servidor Apache/Nginx

1. Copiar archivos al directorio del servidor:
   ```bash
   cp -r database_project /var/www/html/
   ```

2. Configurar permisos:
   ```bash
   chmod 755 /var/www/html/database_project
   ```

3. Acceder desde el navegador (instalación automática)

---

## 📁 Estructura del Proyecto

```
database_project/
├── 📄 index.php              # Página principal - lista de libros paginada
├── 🚀 install.php            # Script de auto-instalación
├── ➕ add.php                # Formulario para agregar libros
├── ✏️ edit.php               # Formulario para editar libros
├── 🗑️ delete.php             # Eliminar libros con confirmación
├── 📂 config/
│   ├── database.php          # Conexión PDO a la BD
│   └── setup.php             # Funciones de instalación
├── 📂 models/
│   ├── Book.php              # Modelo CRUD de libros
│   └── User.php              # Modelo de usuarios
├── 📂 data/                  # Directorio de base de datos
│   └── database.sqlite       # BD SQLite (auto-generado)
├── 📂 assets/
│   ├── 📂 css/
│   │   └── styles.css        # Estilos profesionales
└── 📄 README.md              # Este archivo
```

---

## 🔐 Credenciales por Defecto

```
Usuario:    admin
Contraseña: clave123
```

> **Nota**: El sistema incluye un usuario admin en la BD para referencia futura de autenticación.

---

## 💻 Uso del Sistema

### 📖 Agregar Libros

1. Haz clic en **"➕ Agregar Libro"** en la página principal
2. Completa el formulario:
   - **Título** (obligatorio, 3-255 caracteres)
   - **Autor** (obligatorio, 3-255 caracteres)
   - **Año** (opcional, 1000 - año actual + 10)
   - **Género** (opcional, máximo 100 caracteres)
3. Haz clic en **"💾 Guardar Libro"**
4. Verás un mensaje de confirmación y regresarás al formulario vacío

### 🔍 Buscar Libros

1. En la página principal, usa la **barra de búsqueda**
2. Selecciona dónde buscar:
   - **Por Título**: Busca en los títulos de libros
   - **Por Autor**: Busca en los nombres de autores
3. Escribe tu término de búsqueda y presiona Enter o haz clic en 🔍
4. Los resultados se muestran en la tabla
5. Usa **"Limpiar"** para volver a la lista completa

### ✏️ Editar Libros

1. En la tabla de libros, busca el libro que deseas editar
2. Haz clic en el botón **"✏️ Editar"**
3. Modifica los campos deseados (misma validación que al agregar)
4. Haz clic en **"💾 Actualizar Libro"**
5. Verás un mensaje de confirmación

### 🗑️ Eliminar Libros

1. En la tabla de libros, busca el libro que deseas eliminar
2. Haz clic en el botón **"🗑️ Eliminar"**
3. Se abrirá un **modal de confirmación** mostrando el título
4. Haz clic en **"🗑️ Sí, Eliminar"** para confirmar
5. El libro se eliminará y verás un mensaje de éxito
6. Serás redirigido automáticamente a la lista

### 📄 Paginación

1. En la página principal, verás **números de página** al final
2. Haz clic en un número para ir a esa página
3. Usa **"← Anterior"** y **"Siguiente →"** para navegar
4. Cada página muestra 10 libros

---

## 🎨 Estructura de Datos

### Tabla: books
```
id          INTEGER PRIMARY KEY AUTOINCREMENT
title       TEXT NOT NULL
author      TEXT NOT NULL
year        INTEGER (nullable)
genre       TEXT (nullable)
created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
```

### Tabla: users
```
id          INTEGER PRIMARY KEY AUTOINCREMENT
username    TEXT UNIQUE NOT NULL
password    TEXT NOT NULL (hasheado)
email       TEXT (nullable)
created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
```

---

## 📊 Tecnologías Utilizadas

| Tecnología | Versión | Propósito |
|-----------|---------|-----------|
| PHP | 7.4+ | Backend y lógica |
| SQLite | 3.x | Base de datos |
| PDO | - | Conexión segura a BD |
| HTML5 | - | Estructura |
| CSS3 | - | Estilos responsivos |
| JavaScript | ES6 | Validación en tiempo real |

---

## ✅ Funcionalidades Implementadas

- ✅ Sistema CRUD completo (Create, Read, Update, Delete)
- ✅ Auto-instalación automática
- ✅ Paginación de resultados
- ✅ Búsqueda por título y autor
- ✅ Validación en cliente (JavaScript)
- ✅ Validación en servidor (PHP)
- ✅ Formularios con feedback visual
- ✅ Modal de confirmación para eliminación
- ✅ Protección contra SQL Injection (Prepared Statements)
- ✅ Escape de HTML contra XSS
- ✅ Diseño responsive

---

## 🐛 Solución de Problemas

### Error: "Base de datos no encontrada"
**Solución**: Accede a `http://localhost:8000/install.php` para ejecutar la instalación manualmente.

### Error: "PDO no encontrado"
**Solución**: Verifica que PHP tenga la extensión SQLite habilitada:
```bash
php -m | grep sqlite
```
Si no aparece, instálala con:
```bash
sudo apt-get install php-sqlite3
```

### Error: "No se puede escribir en el directorio"
**Solución**: Asegura permisos de escritura:
```bash
chmod 755 .
```

### Los estilos CSS no se cargan
**Solución**: Verifica que la ruta de `assets/css/styles.css` sea correcta y que el servidor esté sirviendo archivos estáticos correctamente.

---

## 🔄 Flujo de Funcionamiento

```
1. Usuario accede a index.php
   ↓
2. Se verifica si existe database.sqlite
   ├─ NO → Redirige a install.php (auto-instalación)
   └─ SÍ → Continúa
   ↓
3. Se cargan los libros y se muestran paginados
   ↓
4. Usuario interactúa (buscar, agregar, editar, eliminar)
   ├─ Búsqueda → searchByField()
   ├─ Agregar → add()
   ├─ Editar → update()
   └─ Eliminar → delete()
   ↓
5. Feedback visual y redirección
```

---

## 📝 Validaciones Implementadas

### Título y Autor
- Obligatorios
- Mínimo 3 caracteres
- Máximo 255 caracteres
- Se valida en cliente y servidor

### Año
- Opcional
- Si se ingresa: entre 1000 y (año actual + 10)
- Solo números enteros
- Se valida en cliente y servidor

### Género
- Opcional
- Máximo 100 caracteres
- Se valida en cliente y servidor

---

## 🧪 Checklist de Pruebas

- [ ] Acceder al sistema sin instalar (verifica auto-instalación)
- [ ] Agregar 5+ libros diferentes
- [ ] Buscar por título
- [ ] Buscar por autor
- [ ] Limpiar búsqueda
- [ ] Navegar entre páginas
- [ ] Editar un libro
- [ ] Intentar guardar con datos inválidos (verifica validación)
- [ ] Eliminar un libro (verifica confirmación)
- [ ] Probar en diferentes navegadores
- [ ] Probar en dispositivo móvil

---

## 📅 Especificaciones Técnicas

| Aspecto | Especificación |
|--------|----------------|
| Libros por página | 10 |
| Años válidos | 1000 - (año actual + 10) |
| Max. caracteres título/autor | 255 |
| Max. caracteres género | 100 |
| Min. caracteres título/autor | 3 |
| Base de datos | SQLite (file-based) |
| Seguridad queries | Prepared Statements |
| Hashing contraseñas | PASSWORD_DEFAULT |

---

## 👥 Estructura de Clases

### Book.php
```php
- __construct(PDO $pdo)
- getAll(): array
- getById(int $id): ?array
- getPaginated(int $page, int $perPage): array
- search(string $query): array
- searchByField(string $query, string $field): array
- add(string $title, string $author, ?int $year, ?string $genre): bool
- update(int $id, string $title, string $author, ?int $year, ?string $genre): bool
- delete(int $id): bool
```

### User.php
```php
- __construct(PDO $pdo)
- getAll(): array
- getById(int $id): ?array
- getByUsername(string $username): ?array
- add(string $username, string $password, ?string $email): bool
- update(int $id, string $username, ?string $email): bool
- updatePassword(int $id, string $newPassword): bool
- delete(int $id): bool
- verifyPassword(string $plainPassword, string $hashedPassword): bool
```

---

## 📞 Soporte

Para problemas o preguntas sobre el sistema:
- Revisa el README.md (este archivo)
- Verifica los comentarios en el código
- Consulta la estructura de carpetas

---

## 📜 Licencia

Este proyecto es trabajo académico desarrollado para fines educativos.

---

**Desarrollado para el curso de Bases de Datos** 📚✨
