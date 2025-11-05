<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Book.php';

$bookModel = new Book($pdo);

$id = $_GET['id'] ?? null;
$confirmed = $_GET['confirmed'] ?? null;

if (!$id || !$confirmed) {
    header("Location: index.php");
    exit;
}

// Obtener datos del libro para mostrar en el resultado
$book = $bookModel->getById((int)$id);
if (!$book) {
    header("Location: index.php");
    exit;
}

$deleted = false;
$error = false;

// Procesar eliminación
try {
    if ($bookModel->delete((int)$id)) {
        $deleted = true;
    } else {
        $error = true;
    }
} catch (Exception $e) {
    $error = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Libro - Book Manager</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php if ($deleted): ?>
        <!-- Mensaje de éxito -->
        <div class="success-container">
            <div class="success-message">
                <div class="icon">✅</div>
                <h2>¡Libro Eliminado!</h2>
                <p><?= htmlspecialchars($book['title']) ?> ha sido eliminado correctamente.</p>
                <p class="redirect-info">Redirigiendo a la lista de libros...</p>
            </div>
        </div>

        <script>
            // Redirigir después de 2 segundos
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 2000);
        </script>

    <?php elseif ($error): ?>
        <!-- Mensaje de error -->
        <div class="success-container">
            <div class="success-message error-message">
                <div class="icon">❌</div>
                <h2 style="color: #dc3545;">Error al Eliminar</h2>
                <p>No se pudo eliminar el libro. Intenta nuevamente.</p>
                <a href="index.php" class="btn-cancel" style="width: 100%; text-align: center;">Volver a la Lista</a>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
