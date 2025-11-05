<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Book.php';

$bookModel = new Book($pdo);
$error = '';
$success = '';

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: index.php");
    exit;
}

// Obtener datos actuales del libro
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    header("Location: index.php");
    exit;
}

// Procesar formulario
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
        // Todo OK, actualizar libro
        try {
            $bookModel->update($id, $title, $author, $year, $genre);
            $success = "¡Libro actualizado exitosamente!";
            // Actualizar datos para mostrar en el formulario
            $book['title'] = $title;
            $book['author'] = $author;
            $book['year'] = $year;
            $book['genre'] = $genre;
        } catch (Exception $e) {
            $error = "Error al actualizar el libro: " . $e->getMessage();
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
    <title>Editar Libro - Book Manager Pro</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✏️ Editar Libro</h1>
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

        <!-- Información del libro actual -->
        <div style="margin-bottom: 2rem; padding: 1.5rem; background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1)); border-radius: var(--border-radius); border: 2px solid rgba(99, 102, 241, 0.2);">
            <h3 style="margin-bottom: 0.5rem; color: var(--text-primary);">
                📚 Editando: <strong><?= htmlspecialchars($book['title']) ?></strong>
            </h3>
            <p style="color: var(--text-secondary); margin: 0;">
                👤 Autor actual: <?= htmlspecialchars($book['author']) ?>
                <?php if ($book['year']): ?>
                    | 📅 Año: <?= htmlspecialchars($book['year']) ?>
                <?php endif; ?>
            </p>
        </div>

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
                        value="<?= htmlspecialchars($book['title']) ?>"
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
                        value="<?= htmlspecialchars($book['author']) ?>"
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
                        value="<?= htmlspecialchars($book['year'] ?? '') ?>"
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
                        value="<?= htmlspecialchars($book['genre'] ?? '') ?>"
                        list="genresList"
                    >
                    <?php if (count($existingGenres) > 0): ?>
                        <datalist id="genresList">
                            <?php foreach ($existingGenres as $g): ?>
                                <option value="<?= htmlspecialchars($g) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    <?php endif; ?>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        💾 Actualizar Libro
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        ❌ Cancelar
                    </a>
                    <a href="delete.php?id=<?= (int)$book['id'] ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('¿Estás seguro de eliminar este libro?\n\nTítulo: <?= htmlspecialchars($book['title']) ?>\nAutor: <?= htmlspecialchars($book['author']) ?>\n\nEsta acción no se puede deshacer.')"
                       style="margin-left: auto;">
                        🗑️ Eliminar
                    </a>
                </div>
            </form>
        </div>

        <!-- Información adicional -->
        <div style="margin-top: 2rem; padding: 1.5rem; background: var(--bg-secondary); border-radius: var(--border-radius); border-left: 4px solid var(--warning);">
            <h3 style="margin-bottom: 0.75rem; color: var(--text-primary); font-size: 1.125rem;">
                💡 Consejos para editar
            </h3>
            <ul style="color: var(--text-secondary); padding-left: 1.5rem; line-height: 1.8;">
                <li>Los campos marcados con * son obligatorios</li>
                <li>Revisa cuidadosamente los cambios antes de guardar</li>
                <li>Puedes eliminar el libro usando el botón "Eliminar" al final del formulario</li>
                <li>Los cambios se guardarán inmediatamente al hacer clic en "Actualizar Libro"</li>
            </ul>
        </div>
    </div>
</body>
</html>
