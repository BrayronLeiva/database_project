<?php
// Verificar si ya está instalado (BD existe Y config existe)
$dbFile = __DIR__ . '/data/database.sqlite';
$configFile = __DIR__ . '/config/database.php';

// Variable para controlar el estado de instalación
$installed = false;
$error = '';
$alreadyInstalled = false;

// Si YA está instalado, mostrar mensaje pero NO redirigir automáticamente
if (file_exists($dbFile) && file_exists($configFile)) {
    $alreadyInstalled = true;
}

// Procesar instalación solo si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$alreadyInstalled) {
    try {
        require_once __DIR__ . '/config/setup.php';
        
        // Crear directorios necesarios
        $dataDir = __DIR__ . '/data';
        ensureDirectories($dataDir);
        
        // Crear base de datos
        $pdo = createDatabase($dbFile);
        
        // Crear tablas
        createTables($pdo);
        
        // Crear usuario admin
        seedAdmin($pdo);
        
        // Insertar datos de ejemplo
        seedSampleData($pdo);
        
        $installed = true;
        
        // Crear archivo de configuración de BD
        $configContent = "<?php\n";
        $configContent .= "// Conexión PDO a SQLite\n";
        $configContent .= "\$dbPath = __DIR__ . '/../data/database.sqlite';\n";
        $configContent .= "try {\n";
        $configContent .= "    \$pdo = new PDO('sqlite:' . \$dbPath);\n";
        $configContent .= "    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n";
        $configContent .= "    \$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);\n";
        $configContent .= "} catch (PDOException \$e) {\n";
        $configContent .= "    die('Error de conexión: ' . \$e->getMessage());\n";
        $configContent .= "}\n";
        
        file_put_contents($configFile, $configContent);
        
    } catch (Exception $e) {
        $error = "Error durante la instalación: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalación - Book Manager Pro</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container install-container">
        <?php if ($alreadyInstalled && !$installed): ?>
            <!-- Sistema ya instalado -->
            <div class="icon">✅</div>
            <h1>Sistema Ya Instalado</h1>
            <p>Book Manager Pro ya está instalado y configurado</p>

            <div class="alert alert-success" style="text-align: left;">
                <div style="margin-bottom: 1rem;">
                    <strong>✓ El sistema está listo para usar:</strong>
                </div>
                <ul style="padding-left: 1.5rem; line-height: 1.8;">
                    <li>Base de datos SQLite configurada</li>
                    <li>Tablas creadas correctamente</li>
                    <li>Sistema completamente funcional</li>
                </ul>
            </div>

            <div style="margin-top: 2rem;">
                <a href="index.php" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.125rem;">
                    📚 Ir a Mi Biblioteca
                </a>
            </div>

            <div style="margin-top: 2rem; padding: 1.5rem; background: var(--bg-secondary); border-radius: var(--border-radius); border-left: 4px solid var(--warning);">
                <h4 style="margin-bottom: 0.75rem; color: var(--text-primary);">
                    ⚠️ ¿Necesitas reinstalar?
                </h4>
                <p style="color: var(--text-secondary); margin-bottom: 1rem;">
                    Para reinstalar el sistema, elimina los siguientes archivos:
                </p>
                <ul style="color: var(--text-secondary); padding-left: 1.5rem; line-height: 1.8; font-family: monospace; font-size: 0.875rem;">
                    <li>data/database.sqlite</li>
                    <li>config/database.php</li>
                </ul>
            </div>

        <?php elseif (!$installed): ?>
            <!-- Pantalla de instalación inicial -->
            <div class="icon">🚀</div>
            <h1>Bienvenido a Book Manager Pro</h1>
            <p>Sistema de gestión de biblioteca personal con auto-instalación</p>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    ❌ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="install-steps">
                <h3 style="margin-bottom: 1rem; color: var(--text-primary);">
                    📋 Proceso de instalación
                </h3>
                <ol>
                    <li>Se creará la base de datos SQLite</li>
                    <li>Se configurarán las tablas necesarias (books, users)</li>
                    <li>Se creará un usuario administrador por defecto</li>
                    <li>Se insertarán datos de ejemplo para pruebas</li>
                    <li>Se generará el archivo de configuración automáticamente</li>
                </ol>
            </div>

            <div style="background: var(--bg-secondary); padding: 1.5rem; border-radius: var(--border-radius); margin-bottom: 2rem; border: 2px solid var(--primary);">
                <h3 style="margin-bottom: 0.75rem; color: var(--primary);">
                    🔐 Credenciales por defecto
                </h3>
                <div style="background: var(--bg-primary); padding: 1rem; border-radius: var(--border-radius-sm); font-family: 'Courier New', monospace;">
                    <p style="margin-bottom: 0.5rem;"><strong>Usuario:</strong> admin</p>
                    <p style="margin: 0;"><strong>Contraseña:</strong> clave123</p>
                </div>
                <p style="color: var(--text-tertiary); margin-top: 0.75rem; margin-bottom: 0; font-size: 0.875rem;">
                    💡 Estas credenciales son solo para el sistema de usuarios interno
                </p>
            </div>

            <form method="POST" action="">
                <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.125rem;">
                    🚀 Iniciar Instalación
                </button>
            </form>

            <div style="margin-top: 2rem; padding: 1.5rem; background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1)); border-radius: var(--border-radius); border: 2px dashed rgba(99, 102, 241, 0.3);">
                <h4 style="margin-bottom: 0.75rem; color: var(--text-primary);">
                    ✨ Características incluidas
                </h4>
                <ul style="color: var(--text-secondary); padding-left: 1.5rem; line-height: 1.8;">
                    <li>✅ Operaciones CRUD completas</li>
                    <li>✅ Sistema de búsqueda avanzado</li>
                    <li>✅ Estadísticas de tu biblioteca</li>
                    <li>✅ Diseño moderno y responsive</li>
                    <li>✅ Prepared statements para seguridad</li>
                    <li>✅ Base de datos SQLite portable</li>
                </ul>
            </div>

        <?php else: ?>
            <!-- Pantalla de instalación exitosa -->
            <div class="icon">✅</div>
            <h1>¡Instalación Completada!</h1>
            <p>Book Manager Pro ha sido instalado correctamente</p>

            <div class="alert alert-success" style="text-align: left;">
                <div style="margin-bottom: 1rem;">
                    <strong>✓ Se han completado las siguientes tareas:</strong>
                </div>
                <ul style="padding-left: 1.5rem; line-height: 1.8;">
                    <li>Base de datos SQLite creada exitosamente</li>
                    <li>Tablas configuradas (books, users)</li>
                    <li>Usuario administrador creado</li>
                    <li>Datos de ejemplo insertados</li>
                    <li>Archivo de configuración generado</li>
                </ul>
            </div>

            <div style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1)); padding: 2rem; border-radius: var(--border-radius); margin: 2rem 0; border: 2px solid rgba(99, 102, 241, 0.2);">
                <h3 style="margin-bottom: 1rem; color: var(--primary);">
                    🎉 ¡Todo listo para empezar!
                </h3>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
                    Tu biblioteca personal está lista. Puedes comenzar a agregar y gestionar tus libros.
                </p>
                <a href="index.php" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.125rem;">
                    📚 Ir a Mi Biblioteca
                </a>
            </div>

            <div style="background: var(--bg-secondary); padding: 1.5rem; border-radius: var(--border-radius); border-left: 4px solid var(--success);">
                <h4 style="margin-bottom: 0.75rem; color: var(--text-primary);">
                    💡 Próximos pasos sugeridos
                </h4>
                <ol style="color: var(--text-secondary); padding-left: 1.5rem; line-height: 1.8;">
                    <li>Explora los libros de ejemplo incluidos</li>
                    <li>Agrega tus propios libros a la biblioteca</li>
                    <li>Prueba la función de búsqueda</li>
                    <li>Revisa las estadísticas de tu colección</li>
                    <li>Personaliza y edita los libros existentes</li>
                </ol>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
