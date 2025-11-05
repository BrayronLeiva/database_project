<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Book.php';

$bookModel = new Book($pdo);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $year   = $_POST['year'] ?? null;
    $genre  = trim($_POST['genre'] ?? '');

    // Validación
    if (empty($title)) {
        $error = "El título es obligatorio.";
    } elseif (empty($author)) {
        $error = "El autor es obligatorio.";
    } elseif (!empty($year) && (!is_numeric($year) || $year < 1000 || $year > date('Y') + 10)) {
        $error = "El año debe ser válido (entre 1000 y " . (date('Y') + 10) . ").";
    } else {
        // Todo OK, agregar libro
        try {
            $bookModel->add($title, $author, $year, $genre);
            $success = "¡Libro agregado exitosamente!";
            // Limpiar formulario
            $title = $author = $year = $genre = '';
        } catch (Exception $e) {
            $error = "Error al agregar el libro: " . $e->getMessage();
        }
    }
}

// Obtener géneros existentes para sugerencias
$stmt = $pdo->query("SELECT DISTINCT genre FROM books WHERE genre IS NOT NULL AND genre != '' ORDER BY genre");
$existingGenres = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Libro - Book Manager Pro</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>➕ Agregar Nuevo Libro</h1>
            <div class="header-actions">
                <a href="index.php" class="btn btn-secondary">⬅️ Volver</a>
            </div>
        </div>

        <!-- Mensajes -->
        <?php if ($error): ?>
            <div class="alert alert-error">
                ❌ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                ✅ <?= htmlspecialchars($success) ?>
                <a href="index.php" class="btn btn-sm btn-primary" style="margin-left: 1rem;">Ver Biblioteca</a>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <div class="form-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">
                        📖 Título del Libro *
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        placeholder="Ej: Cien años de soledad" 
                        value="<?= isset($title) ? htmlspecialchars($title) : '' ?>"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="author">
                        👤 Autor *
                    </label>
                    <input 
                        type="text" 
                        id="author" 
                        name="author" 
                        placeholder="Ej: Gabriel García Márquez" 
                        value="<?= isset($author) ? htmlspecialchars($author) : '' ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="year">
                        📅 Año de Publicación
                    </label>
                    <input 
                        type="number" 
                        id="year" 
                        name="year" 
                        placeholder="Ej: 1967" 
                        min="1000" 
                        max="<?= date('Y') + 10 ?>"
                        value="<?= isset($year) ? htmlspecialchars($year) : '' ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="genre">
                        🎭 Género Literario
                    </label>
                    <input 
                        type="text" 
                        id="genre" 
                        name="genre" 
                        placeholder="Ej: Ficción, Ciencia Ficción, Romance, etc." 
                        value="<?= isset($genre) ? htmlspecialchars($genre) : '' ?>"
                        list="genresList"
                    >
                    <?php if (count($existingGenres) > 0): ?>
                        <datalist id="genresList">
                            <?php foreach ($existingGenres as $g): ?>
                                <option value="<?= htmlspecialchars($g) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    <?php endif; ?>
                    <small style="color: var(--text-tertiary); display: block; margin-top: 0.25rem;">
                        💡 Tip: Selecciona un género existente o escribe uno nuevo
                    </small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        💾 Guardar Libro
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        ❌ Cancelar
                    </a>
                </div>
            </form>
        </div>

        <!-- Información adicional -->
        <div style="margin-top: 2rem; padding: 1.5rem; background: var(--bg-secondary); border-radius: var(--border-radius); border-left: 4px solid var(--primary);">
            <h3 style="margin-bottom: 0.75rem; color: var(--text-primary); font-size: 1.125rem;">
                📝 Consejos para agregar libros
            </h3>
            <ul style="color: var(--text-secondary); padding-left: 1.5rem; line-height: 1.8;">
                <li>Los campos marcados con * son obligatorios</li>
                <li>Asegúrate de escribir correctamente el título y el autor</li>
                <li>El año debe ser un número válido entre 1000 y <?= date('Y') + 10 ?></li>
                <li>Puedes usar géneros existentes o crear nuevos</li>
            </ul>
        </div>
    </div>
</body>
</html>
