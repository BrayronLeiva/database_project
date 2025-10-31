<?php
require_once __DIR__ . '/config/setup.php';

$dataDir = __DIR__ . '/data';
$dbFile = $dataDir . '/database.sqlite';

// Si la BD ya existe, ir a index
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
    <meta http-equiv="refresh" content="4;url=index.php">
    <style>
        body { font-family: system-ui, sans-serif; }
        .box { max-width: 520px; margin: 40px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
    </style>
</head>
<body>
<div class="box">
    <h2>🔧 Instalación en progreso</h2>
    <p>Creando directorios, base de datos, tablas y usuario admin...</p>
    <p>Serás redirigido automáticamente al sistema.</p>
</div>
</body>
</html>
<?php
// Ejecutar instalación
ensureDirectories($dataDir);
$pdo = createDatabase($dbFile);
createTables($pdo);
seedAdmin($pdo);
seedSampleData($pdo);

// Redirección de respaldo por si el meta refresh no aplica
header("Refresh: 2; url=index.php");
exit;
