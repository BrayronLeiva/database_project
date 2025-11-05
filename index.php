<?php
// Si no existe la BD, redirige a la instalación
$dbFile = __DIR__ . '/data/database.sqlite';
if (!file_exists($dbFile)) {
    header("Location: install.php");
    exit;
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Book.php';

$bookModel = new Book($pdo);

// Usar paginación en lugar de getAll()
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$data = $bookModel->getPaginated($page, 10);
$books = $data['books'];
$totalPages = $data['pages'];
$currentPage = $data['current_page'];

// Variable para búsqueda mejorada
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchField = isset($_GET['field']) ? $_GET['field'] : 'title';

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
        <h1>📚 Lista de Libros</h1>
        
        <!-- Barra de búsqueda mejorada -->
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Buscar..." 
                   value="<?= htmlspecialchars($searchQuery) ?>">
            <select name="field" class="search-select">
                <option value="title" <?= $searchField === 'title' ? 'selected' : '' ?>>Por Título</option>
                <option value="author" <?= $searchField === 'author' ? 'selected' : '' ?>>Por Autor</option>
            </select>
            <button type="submit">🔍 Buscar</button>
            <?php if ($searchQuery): ?>
                <a href="index.php" class="btn-clear">Limpiar</a>
            <?php endif; ?>
        </form>

        <!-- Botón agregar libro -->
        <a href="add.php" class="btn btn-primary">➕ Agregar Libro</a>

        <!-- Tabla de libros -->
        <?php if (empty($books)): ?>
            <p class="empty-message">No hay libros registrados. <a href="add.php">Agrega uno aquí</a></p>
        <?php else: ?>
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
                <?php foreach ($books as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['id']) ?></td>
                    <td><?= htmlspecialchars($b['title']) ?></td>
                    <td><?= htmlspecialchars($b['author']) ?></td>
                    <td><?= htmlspecialchars($b['year'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($b['genre'] ?? 'N/A') ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?= (int)$b['id'] ?>" class="btn-edit">✏️ Editar</a>
                        <a href="#" class="btn-delete" data-id="<?= (int)$b['id'] ?>" data-title="<?= htmlspecialchars($b['title']) ?>" onclick="return false;">🗑️ Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación (si no hay búsqueda activa) -->
        <?php if (!$searchQuery && $totalPages > 1): ?>
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?>" class="btn">← Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $currentPage): ?>
                    <span class="current-page"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>" class="btn"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>" class="btn">Siguiente →</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div id="deleteModal" class="modal-overlay" style="display: none;">
        <div class="modal">
            <div class="modal-header">
                <span class="icon">⚠️</span>
                <h2>Confirmar Eliminación</h2>
            </div>

            <div class="modal-body">
                <p><strong>¿Estás seguro de que deseas eliminar este libro?</strong></p>
                <p style="color: #666; font-size: 0.95rem;">Esta acción no se puede deshacer.</p>

                <div class="book-info">
                    <p><strong>Título:</strong> <span id="modalBookTitle"></span></p>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeDeleteModal()">❌ Cancelar</button>
                <form method="POST" style="display: contents;" id="deleteForm">
                    <input type="hidden" name="confirm_delete" value="yes">
                    <input type="hidden" name="delete_id" id="deleteId">
                    <button type="submit" class="btn-danger">🗑️ Sí, Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const deleteButtons = document.querySelectorAll('.btn-delete');

        // Abrir modal
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const bookId = this.getAttribute('data-id');
                const bookTitle = this.getAttribute('data-title');
                
                document.getElementById('modalBookTitle').textContent = bookTitle;
                document.getElementById('deleteId').value = bookId;
                deleteModal.style.display = 'flex';
            });
        });

        // Cerrar modal
        function closeDeleteModal() {
            deleteModal.style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Enviar eliminación
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const bookId = document.getElementById('deleteId').value;
            window.location.href = 'delete.php?id=' + bookId + '&confirmed=1';
        });
    </script>
</body>
</html>
