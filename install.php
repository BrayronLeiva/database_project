<?php
// Carga el archivo setup.php que contiene las funciones de instalación
require_once __DIR__ . '/config/setup.php';

// Define la ruta a la carpeta de datos
$dataDir = __DIR__ . '/data';
// Define la ruta completa al archivo de la base de datos SQLite
$dbFile = $dataDir . '/database.sqlite';

// Si la BD ya existe, significa que el proyecto ya está instalado, así que redirige a index
if (file_exists($dbFile)) {
    header("Location: index.php");
    exit;
}

// Mostrar feedback de instalación (HTML primero)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instalando Book Manager</title>
    <!-- Redirige automáticamente a index.php después de 4 segundos -->
    <meta http-equiv="refresh" content="4;url=index.php">
    <style>
        <!-- Estilos básicos para centrar y dar forma a la página -->
        body { 
            font-family: system-ui, sans-serif; 
        }
        .box { 
            max-width: 520px; 
            margin: 40px auto; 
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
        }
    </style>
</head>
<body>
<div class="box">
    <!-- Mensaje indicando que la instalación está en progreso -->
    <h2>🔧 Instalación en progreso</h2>
    <p>Creando directorios, base de datos, tablas y usuario admin...</p>
    <p>Serás redirigido automáticamente al sistema.</p>
</div>
</body>
</html>
<?php
// ========== EJECUTAR INSTALACIÓN ==========

// 1. Crea la carpeta /data si no existe
ensureDirectories($dataDir);

// 2. Crea la conexión a la base de datos SQLite
$pdo = createDatabase($dbFile);

// 3. Crea las tablas: books y users
createTables($pdo);

// 4. Inserta el usuario administrador por defecto
seedAdmin($pdo);

// 5. Inserta 15 libros de ejemplo en la tabla books
seedSampleData($pdo);

// ========== REDIRECCIÓN DE RESPALDO ==========
// Redirección de respaldo por si el meta http-equiv="refresh" no funciona correctamente
// Redirige a index.php después de 2 segundos
header("Refresh: 2; url=index.php");
exit;
