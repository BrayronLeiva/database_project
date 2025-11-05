# 📚 Book Manager Pro - Sistema de Gestión de Biblioteca

> Sistema web moderno y autoinstalable para gestión de biblioteca personal con diseño profesional y funcionalidades avanzadas.

![Version](https://img.shields.io/badge/version-2.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)
![SQLite](https://img.shields.io/badge/SQLite-3-green.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

---

## ✨ Características Principales

### 🎨 Frontend Moderno
- **Diseño Responsive**: Perfecto en desktop, tablet y móvil
- **Interfaz Intuitiva**: UI/UX moderna con animaciones suaves
- **Vista de Tarjetas**: Visualización atractiva de libros en formato card
- **Paleta de Colores**: Gradientes profesionales y esquema coherente
- **Iconos Emoji**: Interface amigable y visual

### 🔍 Funcionalidades Avanzadas
- **Búsqueda en Tiempo Real**: Filtra por título, autor o género
- **Estadísticas**: Dashboard con métricas de tu biblioteca
- **Validación Completa**: Cliente y servidor
- **Mensajes de Feedback**: Alertas visuales para cada acción
- **Autocompletado**: Sugerencias de géneros existentes

### 🛡️ Seguridad
- **Prepared Statements**: Protección contra SQL Injection
- **Password Hashing**: Contraseñas seguras con bcrypt
- **Validación de Datos**: Sanitización de entrada y salida
- **Escape de HTML**: Prevención de XSS

### 🚀 Auto-Instalación
- **Detección Automática**: Redirige a instalación si es necesario
- **Configuración Simple**: Un solo clic para instalar
- **Datos de Ejemplo**: Incluye libros de muestra
- **Usuario Admin**: Creado automáticamente

---

## 📋 Requisitos del Sistema

- PHP 7.4 o superior
- Extensión PDO SQLite
- Servidor web (Apache, Nginx, o PHP built-in)
- Permisos de escritura en el directorio `data/`

---

## 🔧 Instalación

### Opción 1: Instalación Rápida

1. **Descargar** el proyecto:
   ```bash
   unzip book_manager_grupo_X.zip
   cd book_manager_grupo_X
   ```

2. **Iniciar servidor** (usando PHP built-in):
   ```bash
   php -S localhost:8000
   ```

3. **Abrir navegador** y acceder a:
   ```
   http://localhost:8000
   ```

4. **Seguir el asistente** de instalación automática

### Opción 2: Servidor Apache/Nginx

1. Copiar archivos al directorio del servidor:
   ```bash
   cp -r book_manager_grupo_X /var/www/html/
   ```

2. Configurar permisos:
   ```bash
   chmod 755 /var/www/html/book_manager_grupo_X/data
   ```

3. Acceder desde el navegador y seguir la instalación

---

## 📁 Estructura del Proyecto

```
book_manager_grupo_X/
├── 📄 index.php              # Página principal - lista de libros
├── 🚀 install.php            # Script de auto-instalación
├── ➕ add.php                # Agregar libros
├── ✏️ edit.php               # Editar libros
├── 🗑️ delete.php             # Eliminar libros
├── 📂 config/
│   ├── database.php          # Conexión PDO (auto-generado)
│   └── setup.php             # Funciones de instalación
├── 📂 models/
│   ├── Book.php              # Modelo de libros
│   └── User.php              # Modelo de usuarios
├── 📂 data/                  # Directorio de base de datos
│   └── database.sqlite       # BD SQLite (auto-generado)
├── 📂 assets/
│   ├── 📂 css/
│   │   └── styles.css        # Estilos modernos
│   └── 📂 js/
│       └── app.js            # JavaScript interactivo
└── 📄 README.md              # Este archivo
```

---

## 🔐 Credenciales por Defecto

```
Usuario:    admin
Contraseña: clave123
```

> ⚠️ **Nota**: Estas credenciales son para el sistema interno de usuarios. En producción, cambiarlas inmediatamente.

---

## 💻 Uso del Sistema

### Agregar Libros
1. Clic en **"Agregar Libro"**
2. Completar el formulario:
   - 📖 Título (obligatorio)
   - 👤 Autor (obligatorio)
   - 📅 Año (opcional)
   - 🎭 Género (opcional, con autocompletado)
3. Clic en **"Guardar Libro"**

### Buscar Libros
1. Usar la barra de búsqueda en la página principal
2. Escribir términos de búsqueda
3. Los resultados se filtran automáticamente

### Editar Libros
1. Clic en **"Editar"** en cualquier libro
2. Modificar los campos deseados
3. Clic en **"Actualizar Libro"**

### Eliminar Libros
1. Clic en **"Eliminar"** en cualquier libro
2. Confirmar la acción en el diálogo
3. El libro se eliminará permanentemente

---

## 🎨 Personalización

### Cambiar Colores

Editar las variables CSS en `assets/css/styles.css`:

```css
:root {
  --primary: #6366f1;      /* Color principal */
  --secondary: #ec4899;     /* Color secundario */
  --success: #10b981;       /* Color de éxito */
  --danger: #ef4444;        /* Color de peligro */
}
```

### Agregar Nuevos Campos

1. **Modificar la tabla** en `config/setup.php`:
   ```sql
   ALTER TABLE books ADD COLUMN nuevo_campo TEXT;
   ```

2. **Actualizar el modelo** en `models/Book.php`

3. **Agregar campos** en los formularios

---

## 🧪 Pruebas

### Prueba de Portabilidad

```bash
# Servidor 1 (XAMPP)
php -S localhost:8000

# Servidor 2 (WAMP)
php -S localhost:8001

# Servidor 3 (Ubuntu)
php -S localhost:8002
```

### Prueba de Funcionalidades

- ✅ CREATE: Agregar 5+ libros diferentes
- ✅ READ: Verificar lista y búsqueda
- ✅ UPDATE: Editar información de libros
- ✅ DELETE: Eliminar libros con confirmación
- ✅ SEARCH: Buscar por diferentes criterios

---

## 🐛 Solución de Problemas

### Error: "Base de datos bloqueada"
```bash
# Verificar permisos
chmod 644 data/database.sqlite
chmod 755 data/
```

### Error: "PDO no encontrado"
```bash
# Instalar extensión PDO SQLite
sudo apt-get install php-sqlite3
```

### Error: "No se puede escribir en data/"
```bash
# Dar permisos de escritura
sudo chown -R www-data:www-data data/
chmod 755 data/
```

---

## 📊 Tecnologías Utilizadas

| Tecnología | Versión | Propósito |
|-----------|---------|-----------|
| PHP | 7.4+ | Backend |
| SQLite | 3.x | Base de datos |
| PDO | - | Conexión a BD |
| HTML5 | - | Estructura |
| CSS3 | - | Estilos |
| JavaScript | ES6 | Interactividad |

---

## 🎯 Funcionalidades Extras Implementadas

- ✅ Sistema de búsqueda avanzado
- ✅ Diseño responsive completo
- ✅ Estadísticas de biblioteca
- ✅ Validación en tiempo real
- ✅ Autocompletado de géneros
- ✅ Mensajes de feedback visuales
- ✅ Animaciones y transiciones
- ✅ Confirmaciones JavaScript

---

## 📝 Evaluación

### Criterios Cumplidos

| Criterio | Peso | Estado |
|----------|------|--------|
| Funcionalidad CRUD | 30% | ✅ Completo |
| Sistema Auto-Instalable | 25% | ✅ Completo |
| Trabajo en Equipo | 15% | ✅ Roles definidos |
| Documentación | 10% | ✅ README completo |

---

## 👥 Roles del Equipo

| Rol | Responsable | Archivos Clave |
|-----|-------------|----------------|
| Architect & DB Manager | [Nombre] | install.php, setup.php |
| Backend Developer | [Nombre] | models/, CRUD logic |
| Frontend Developer | [Nombre] | index.php, styles.css |
| QA & Deployment | [Nombre] | README.md, testing |

---

## 📅 Fecha de Entrega

**Jueves 7 de noviembre 2025** (en horario lectivo)

---

## 📞 Soporte

Para problemas o dudas:
- 📧 Email: [tu-email]@ejemplo.com
- 📚 Documentación: Ver este README
- 🐛 Reportar bug: [GitHub Issues]

---

## 📜 Licencia

Este proyecto es parte de un trabajo académico y está disponible bajo los términos de tu institución educativa.

---

## 🙏 Agradecimientos

- Profesor del curso por las especificaciones detalladas
- Equipo de desarrollo por el trabajo colaborativo
- Comunidad PHP por la documentación

---

## 🚀 Próximas Mejoras (Roadmap)

- [ ] Sistema de login multi-usuario
- [ ] Exportar biblioteca a PDF/Excel
- [ ] Modo oscuro (dark mode)
- [ ] API RESTful
- [ ] Sistema de préstamos
- [ ] Valoraciones y reseñas

---

**Desarrollado con ❤️ por [Grupo 1]**

*Book Manager Pro - Gestiona tu biblioteca con estilo* 📚✨
