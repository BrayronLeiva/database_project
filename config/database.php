<?php
$dbFile = __DIR__ . '/../data/database.sqlite';

if (!file_exists($dbFile)) {
    // Evitar uso directo sin instalación
    die("Base de datos no encontrada. Ve a install.php para instalar.");
}

try {
    $pdo = new PDO("sqlite:" . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Throwable $e) {
    die("Error de conexión: " . htmlspecialchars($e->getMessage()));
}
