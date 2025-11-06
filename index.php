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

// Instancia la clase Book para acceder a los métodos
$bookModel = new Book($pdo);

// Obtiene la página actual desde la URL (por defecto página 1)
// max() asegura que no sea menor a 1
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
// Obtiene 10 libros por página usando paginación
$data = $bookModel->getPaginated($page, 10);
$books = $data['books'];
$totalPages = $data['pages'];
$currentPage = $data['current_page'];

// Obtiene la palabra clave de búsqueda desde la URL (si existe)
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
// Obtiene el campo de búsqueda: 'title' o 'author' (por defecto 'title')
$searchField = isset($_GET['field']) ? $_GET['field'] : 'title';

// Si hay búsqueda, sobrescribe los libros con los resultados de la búsqueda
if ($searchQuery) {
    $books = $bookModel->searchByField($searchQuery, $searchField);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Manager - Lista de Libros</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Título principal -->
        <h1>📚 Lista de Libros</h1>
        
        <!-- Barra de búsqueda mejorada con selector de campo -->
        <form method="GET" class="search-form">
            <!-- Input de búsqueda con valor actual -->
            <input type="text" name="search" placeholder="Buscar..." 
                   value="<?= htmlspecialchars($searchQuery) ?>">
            
            <!-- Selector para elegir buscar por título o autor -->
            <select name="field" class="search-select">
                <option value="title" <?= $searchField === 'title' ? 'selected' : '' ?>>Por Título</option>
                <option value="author" <?= $searchField === 'author' ? 'selected' : '' ?>>Por Autor</option>
            </select>
            
            <!-- Botón buscar -->
            <button type="submit">🔍 Buscar</button>
            
            <!-- Botón limpiar búsqueda (solo aparece si hay búsqueda activa) -->
            <?php if ($searchQuery): ?>
                <a href="index.php" class="btn-clear">Limpiar</a>
            <?php endif; ?>
        </form>

        <!-- Botón para agregar un nuevo libro -->
        <a href="add.php" class="btn btn-primary">➕ Agregar Libro</a>

        <!-- Tabla de libros -->
        <!-- Si no hay libros, muestra mensaje vacío -->
        <?php if (empty($books)): ?>
            <p class="empty-message">No hay libros registrados. <a href="add.php">Agrega uno aquí</a></p>
        <?php else: ?>
        <!-- Tabla con lista de libros -->
        <table class="books-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Año</th>
                    <th>Género</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Itera sobre cada libro y lo muestra en una fila -->
                <?php foreach ($books as $b): ?>
                <tr>
                    <!-- ID del libro -->
                    <td><?= htmlspecialchars($b['id']) ?></td>
                    <!-- Título del libro -->
                    <td><?= htmlspecialchars($b['title']) ?></td>
                    <!-- Autor del libro -->
                    <td><?= htmlspecialchars($b['author']) ?></td>
                    <!-- Año (muestra N/A si está vacío) -->
                    <td><?= htmlspecialchars($b['year'] ?? 'N/A') ?></td>
                    <!-- Género (muestra N/A si está vacío) -->
                    <td><?= htmlspecialchars($b['genre'] ?? 'N/A') ?></td>
                    <!-- Botones de acción: Editar y Eliminar -->
                    <td class="actions">
                        <!-- Botón Editar: redirige a edit.php con el ID del libro -->
                        <a href="edit.php?id=<?= (int)$b['id'] ?>" class="btn-edit">✏️ Editar</a>
                        <!-- Botón Eliminar: abre modal de confirmación -->
                        <a href="#" class="btn-delete" data-id="<?= (int)$b['id'] ?>" data-title="<?= htmlspecialchars($b['title']) ?>" onclick="return false;">🗑️ Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación (solo se muestra si NO hay búsqueda activa y hay más de 1 página) -->
        <?php if (!$searchQuery && $totalPages > 1): ?>
        <div class="pagination">
            <!-- Botón Anterior (solo si no estamos en la página 1) -->
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?>" class="btn">← Anterior</a>
            <?php endif; ?>

            <!-- Botones numerados de páginas -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <!-- Si es la página actual, muestra como texto, sino como link -->
                <?php if ($i == $currentPage): ?>
                    <span class="current-page"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>" class="btn"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <!-- Botón Siguiente (solo si no estamos en la última página) -->
            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>" class="btn">Siguiente →</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </div>

    <!-- Modal de confirmación antes de eliminar un libro -->
    <div id="deleteModal" class="modal-overlay" style="display: none;">
        <div class="modal">
            <!-- Encabezado del modal -->
            <div class="modal-header">
                <span class="icon">⚠️</span>
                <h2>Confirmar Eliminación</h2>
            </div>

            <!-- Cuerpo del modal con detalles -->
            <div class="modal-body">
                <p><strong>¿Estás seguro de que deseas eliminar este libro?</strong></p>
                <p style="color: #666; font-size: 0.95rem;">Esta acción no se puede deshacer.</p>

                <!-- Información del libro a eliminar -->
                <div class="book-info">
                    <p><strong>Título:</strong> <span id="modalBookTitle"></span></p>
                </div>
            </div>

            <!-- Pie del modal con botones de acción -->
            <div class="modal-footer">
                <!-- Botón Cancelar -->
                <button class="btn-cancel" onclick="closeDeleteModal()">❌ Cancelar</button>
                <!-- Formulario para enviar eliminación -->
                <form method="POST" style="display: contents;" id="deleteForm">
                    <input type="hidden" name="confirm_delete" value="yes">
                    <!-- Campo oculto que almacena el ID del libro a eliminar -->
                    <input type="hidden" name="delete_id" id="deleteId">
                    <!-- Botón Sí, Eliminar -->
                    <button type="submit" class="btn-danger">🗑️ Sí, Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Obtiene referencias a los elementos del modal y botones de eliminar
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const deleteButtons = document.querySelectorAll('.btn-delete');

        // Abre el modal al hacer clic en un botón Eliminar
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                // Obtiene el ID y título del libro desde los atributos data-*
                const bookId = this.getAttribute('data-id');
                const bookTitle = this.getAttribute('data-title');
                
                // Actualiza el modal con el título del libro
                document.getElementById('modalBookTitle').textContent = bookTitle;
                // Almacena el ID del libro en el campo oculto
                document.getElementById('deleteId').value = bookId;
                // Muestra el modal
                deleteModal.style.display = 'flex';
            });
        });

        // Cierra el modal de confirmación
        function closeDeleteModal() {
            deleteModal.style.display = 'none';
        }

        // Cierra el modal si se hace clic fuera del modal (en el overlay)
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Envía la eliminación cuando se confirma
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const bookId = document.getElementById('deleteId').value;
            // Redirige a delete.php con el ID y confirmación
            window.location.href = 'delete.php?id=' + bookId + '&confirmed=1';
        });
    </script>
</body>
</html>
