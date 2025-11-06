<?php
// Carga la conexión a la BD y la clase Book
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Book.php';

// Instancia la clase Book para acceder a los métodos de eliminación
$bookModel = new Book($pdo);

// Obtiene el ID del libro a eliminar y la confirmación de la URL
$id = $_GET['id'] ?? null;
$confirmed = $_GET['confirmed'] ?? null;

// Si falta el ID o la confirmación, redirige a la lista para evitar eliminaciones accidentales
if (!$id || !$confirmed) {
    header("Location: index.php");
    exit;
}

// Obtiene los datos del libro para mostrar en el mensaje de resultado
$book = $bookModel->getById((int)$id);
if (!$book) {
    // Si el libro no existe, redirige a la lista
    header("Location: index.php");
    exit;
}

// Inicializa variables para controlar el resultado de la eliminación
$deleted = false;
$error = false;

// Intenta eliminar el libro de la base de datos
try {
    if ($bookModel->delete((int)$id)) {
        // Si la eliminación es exitosa, marca como eliminado
        $deleted = true;
    } else {
        // Si la eliminación falla, marca como error
        $error = true;
    }
} catch (Exception $e) {
    // Si hay una excepción, marca como error
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
    <!-- Muestra mensaje de éxito si el libro se eliminó correctamente -->
    <?php if ($deleted): ?>
        <div class="success-container">
            <div class="success-message">
                <!-- Icono de confirmación -->
                <div class="icon">✅</div>
                <h2>¡Libro Eliminado!</h2>
                <!-- Muestra el título del libro eliminado -->
                <p><?= htmlspecialchars($book['title']) ?> ha sido eliminado correctamente.</p>
                <p class="redirect-info">Redirigiendo a la lista de libros...</p>
            </div>
        </div>

        <!-- Redirigir automáticamente a index.php después de 2 segundos -->
        <script>
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 2000);
        </script>

    <!-- Muestra mensaje de error si la eliminación falló -->
    <?php elseif ($error): ?>
        <div class="success-container">
            <div class="success-message error-message">
                <!-- Icono de error -->
                <div class="icon">❌</div>
                <h2 style="color: #dc3545;">Error al Eliminar</h2>
                <p>No se pudo eliminar el libro. Intenta nuevamente.</p>
                <!-- Botón para volver a la lista manualmente -->
                <a href="index.php" class="btn-cancel" style="width: 100%; text-align: center;">Volver a la Lista</a>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
