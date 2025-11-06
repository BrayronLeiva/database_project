<?php
// Define la ruta absoluta al archivo de la base de datos SQLite
$dbFile = __DIR__ . '/../data/database.sqlite';

// Verifica que la base de datos exista, si no obliga a instalar primero
if (!file_exists($dbFile)) {
    die("Base de datos no encontrada. Ve a install.php para instalar.");
}

try {
    // Establece conexión a la base de datos SQLite
    $pdo = new PDO("sqlite:" . $dbFile);
    // Configura que lance excepciones si hay errores en la BD
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Throwable $e) {
    // Captura cualquier error de conexión y lo muestra de forma segura
    die("Error de conexión: " . htmlspecialchars($e->getMessage()));
}
