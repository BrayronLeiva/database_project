<?php
// Si no existe la BD, redirige a la instalación
$dbFile = __DIR__ . '/data/database.sqlite';
if (!file_exists($dbFile)) {
    header("Location: install.php");
    exit;
}

// Carga la conexión a la BD y la clase Book
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Book.php';

// Inicializa la clase Book y las variables del formulario
$bookModel = new Book($pdo);
$errors = [];
$success = false;

// Obtiene el ID del libro a editar desde la URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

// Obtiene los datos actuales del libro
$book = $bookModel->getById((int)$id);
if (!$book) {
    // Si el libro no existe, redirige a la lista
    header("Location: index.php");
    exit;
}

// Carga los valores actuales del libro en variables para mostrar en el formulario
$title = $book['title'];
$author = $book['author'];
$year = $book['year'] ?? '';
$genre = $book['genre'] ?? '';

// Procesa el formulario cuando se envía por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene y limpia los datos del formulario
    $title = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');
    $year = isset($_POST['year']) && $_POST['year'] !== '' ? (int)$_POST['year'] : null;
    $genre = trim($_POST['genre'] ?? '');

    // Validaciones de servidor para el título
    if (empty($title)) {
        $errors[] = 'El título es obligatorio.';
    } elseif (strlen($title) < 3) {
        $errors[] = 'El título debe tener al menos 3 caracteres.';
    } elseif (strlen($title) > 255) {
        $errors[] = 'El título no puede exceder 255 caracteres.';
    }

    // Validaciones de servidor para el autor
    if (empty($author)) {
        $errors[] = 'El autor es obligatorio.';
    } elseif (strlen($author) < 3) {
        $errors[] = 'El autor debe tener al menos 3 caracteres.';
    } elseif (strlen($author) > 255) {
        $errors[] = 'El autor no puede exceder 255 caracteres.';
    }

    // Validación del año (opcional pero con rango límite)
    if ($year !== null && ($year < 1000 || $year > date('Y') + 10)) {
        $errors[] = 'El año debe estar entre 1000 y ' . (date('Y') + 10) . '.';
    }

    // Validación del género (opcional pero con límite de caracteres)
    if (!empty($genre) && strlen($genre) > 100) {
        $errors[] = 'El género no puede exceder 100 caracteres.';
    }

    // Si no hay errores, intenta actualizar el libro en la BD
    if (empty($errors)) {
        try {
            if ($bookModel->update($id, $title, $author, $year, $genre ?: null)) {
                $success = true;
            } else {
                $errors[] = 'Error al actualizar el libro. Intenta nuevamente.';
            }
        } catch (Exception $e) {
            $errors[] = 'Error en la base de datos: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Libro - Book Manager</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Encabezado con botón de regreso -->
        <div class="form-header">
            <a href="index.php" class="btn-back">← Volver a Lista</a>
            <h1>✏️ Editar Libro</h1>
        </div>

        <!-- Muestra mensaje de éxito si el libro se actualizó correctamente -->
        <?php if ($success): ?>
        <div class="alert alert-success">
            ✅ ¡Libro actualizado exitosamente! 
            <a href="index.php" style="color: inherit; font-weight: 600;">Ver todos los libros</a>
        </div>
        <?php endif; ?>

        <!-- Muestra lista de errores si hay validaciones fallidas -->
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <strong>❌ Error(es) encontrado(s):</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Formulario con validación HTML5 (novalidate permite JS personalizado) -->
        <form method="POST" class="book-form" id="bookForm" novalidate>
            <!-- Campo: Título (obligatorio) -->
            <div class="form-group">
                <label for="title">Título del Libro *</label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    placeholder="Ej: El Quijote"
                    value="<?= htmlspecialchars($title) ?>"
                    required 
                    minlength="3" 
                    maxlength="255"
                    class="form-input"
                    data-field="title"
                >
                <small class="form-text form-help" id="title-help">Mínimo 3 caracteres, máximo 255</small>
                <small class="form-text form-error" id="title-error"></small>
            </div>

            <!-- Campo: Autor (obligatorio) -->
            <div class="form-group">
                <label for="author">Autor *</label>
                <input 
                    type="text" 
                    id="author" 
                    name="author" 
                    placeholder="Ej: Miguel de Cervantes"
                    value="<?= htmlspecialchars($author) ?>"
                    required 
                    minlength="3" 
                    maxlength="255"
                    class="form-input"
                    data-field="author"
                >
                <small class="form-text form-help" id="author-help">Mínimo 3 caracteres, máximo 255</small>
                <small class="form-text form-error" id="author-error"></small>
            </div>

            <!-- Fila con dos campos: Año y Género -->
            <div class="form-row">
                <!-- Campo: Año de Publicación (opcional) -->
                <div class="form-group">
                    <label for="year">Año de Publicación</label>
                    <input 
                        type="number" 
                        id="year" 
                        name="year" 
                        placeholder="Ej: 2024"
                        value="<?= htmlspecialchars($year) ?>"
                        min="1000" 
                        max="<?= date('Y') + 10 ?>"
                        class="form-input"
                        data-field="year"
                    >
                    <small class="form-text form-help" id="year-help">Opcional (1000 - <?= date('Y') + 10 ?>)</small>
                    <small class="form-text form-error" id="year-error"></small>
                </div>

                <!-- Campo: Género (opcional) -->
                <div class="form-group">
                    <label for="genre">Género</label>
                    <input 
                        type="text" 
                        id="genre" 
                        name="genre" 
                        placeholder="Ej: Novela, Ciencia Ficción"
                        value="<?= htmlspecialchars($genre) ?>"
                        maxlength="100"
                        class="form-input"
                        data-field="genre"
                    >
                    <small class="form-text form-help" id="genre-help">Opcional (máximo 100 caracteres)</small>
                    <small class="form-text form-error" id="genre-error"></small>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="submitBtn">💾 Actualizar Libro</button>
                <a href="index.php" class="btn btn-secondary">✖️ Cancelar</a>
            </div>
        </form>

        <!-- Pie de página con nota sobre campos obligatorios -->
        <div class="form-footer">
            <p>* Los campos marcados con asterisco son obligatorios</p>
        </div>
    </div>

    <script>
        // Obtiene referencias a los elementos del formulario
        const form = document.getElementById('bookForm');
        const titleInput = document.getElementById('title');
        const authorInput = document.getElementById('author');
        const yearInput = document.getElementById('year');
        const genreInput = document.getElementById('genre');

        // Valida el título: obligatorio, 3-255 caracteres
        function validateTitle(value) {
            if (value.trim().length === 0) {
                return { valid: false, message: '❌ El título es obligatorio' };
            } else if (value.length < 3) {
                return { valid: false, message: '❌ El título debe tener al menos 3 caracteres' };
            } else if (value.length > 255) {
                return { valid: false, message: '❌ El título no puede exceder 255 caracteres' };
            }
            return { valid: true, message: '✅ Título válido' };
        }

        // Valida el autor: obligatorio, 3-255 caracteres
        function validateAuthor(value) {
            if (value.trim().length === 0) {
                return { valid: false, message: '❌ El autor es obligatorio' };
            } else if (value.length < 3) {
                return { valid: false, message: '❌ El autor debe tener al menos 3 caracteres' };
            } else if (value.length > 255) {
                return { valid: false, message: '❌ El autor no puede exceder 255 caracteres' };
            }
            return { valid: true, message: '✅ Autor válido' };
        }

        // Valida el año: opcional, pero si se proporciona debe estar entre 1000 y año actual + 10
        function validateYear(value) {
            const currentYear = new Date().getFullYear();
            const maxYear = currentYear + 10;
            
            if (value === '') {
                return { valid: true, message: 'Opcional' };
            }
            
            const yearNum = parseInt(value);
            if (isNaN(yearNum) || yearNum < 1000 || yearNum > maxYear) {
                return { valid: false, message: `❌ El año debe estar entre 1000 y ${maxYear}` };
            }
            return { valid: true, message: '✅ Año válido' };
        }

        // Valida el género: opcional, pero máximo 100 caracteres
        function validateGenre(value) {
            if (value.length > 100) {
                return { valid: false, message: '❌ El género no puede exceder 100 caracteres' };
            }
            return { valid: true, message: value.length === 0 ? 'Opcional' : '✅ Género válido' };
        }

        // Actualiza visualmente el estado del input (colores y mensajes de error)
        function updateFieldStatus(input, validation) {
            const errorElement = document.getElementById(`${input.id}-error`);
            const helpElement = document.getElementById(`${input.id}-help`);

            if (validation.valid) {
                // Si es válido: verde, oculta error, muestra ayuda
                input.classList.remove('input-error');
                input.classList.add('input-valid');
                errorElement.textContent = '';
                helpElement.style.display = 'block';
            } else {
                // Si es inválido: rojo, muestra error, oculta ayuda
                input.classList.add('input-error');
                input.classList.remove('input-valid');
                errorElement.textContent = validation.message;
                helpElement.style.display = 'none';
            }
        }

        // Valida título mientras el usuario escribe
        titleInput.addEventListener('input', function() {
            const validation = validateTitle(this.value);
            updateFieldStatus(this, validation);
        });

        // Valida autor mientras el usuario escribe
        authorInput.addEventListener('input', function() {
            const validation = validateAuthor(this.value);
            updateFieldStatus(this, validation);
        });

        // Valida año mientras el usuario escribe
        yearInput.addEventListener('input', function() {
            const validation = validateYear(this.value);
            updateFieldStatus(this, validation);
        });

        // Valida género mientras el usuario escribe
        genreInput.addEventListener('input', function() {
            const validation = validateGenre(this.value);
            updateFieldStatus(this, validation);
        });

        // Valida todos los campos al enviar el formulario
        form.addEventListener('submit', function(e) {
            const titleValidation = validateTitle(titleInput.value);
            const authorValidation = validateAuthor(authorInput.value);
            const yearValidation = validateYear(yearInput.value);
            const genreValidation = validateGenre(genreInput.value);

            // Actualiza estado visual de todos los campos
            updateFieldStatus(titleInput, titleValidation);
            updateFieldStatus(authorInput, authorValidation);
            updateFieldStatus(yearInput, yearValidation);
            updateFieldStatus(genreInput, genreValidation);

            // Si hay errores, previene envío y desplaza a los errores
            if (!titleValidation.valid || !authorValidation.valid || !yearValidation.valid || !genreValidation.valid) {
                e.preventDefault();
                document.querySelector('.alert-danger')?.scrollIntoView({ behavior: 'smooth' });
            }
        });

        // Al cargar la página, valida campos si tienen valores existentes
        window.addEventListener('load', function() {
            if (titleInput.value) updateFieldStatus(titleInput, validateTitle(titleInput.value));
            if (authorInput.value) updateFieldStatus(authorInput, validateAuthor(authorInput.value));
            if (yearInput.value) updateFieldStatus(yearInput, validateYear(yearInput.value));
            if (genreInput.value) updateFieldStatus(genreInput, validateGenre(genreInput.value));
        });
    </script>
</body>
</html>
