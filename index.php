<?php
// Verificar si existe la base de datos Y el archivo de configuración
$dbFile = __DIR__ . '/data/database.sqlite';
$configFile = __DIR__ . '/config/database.php';

// Si no existe la BD O no existe el config, redirigir a instalación
if (!file_exists($dbFile) || !file_exists($configFile)) {
    // Solo redirigir si NO estamos ya en install.php
    $currentScript = basename($_SERVER['PHP_SELF']);
    if ($currentScript !== 'install.php') {
        header("Location: install.php");
        exit;
    }
}

// Si llegamos aquí, todo está bien instalado
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Book.php';

$bookModel = new Book($pdo);

// Obtener término de búsqueda
$searchTerm = $_GET['search'] ?? '';

// Obtener todos los libros o filtrados
if ($searchTerm) {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR genre LIKE ? ORDER BY created_at DESC");
    $searchParam = "%$searchTerm%";
    $stmt->execute([$searchParam, $searchParam, $searchParam]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $books = $bookModel->getAll();
}

// Calcular estadísticas
$totalBooks = count($bookModel->getAll());
$genres = [];
$years = [];
foreach ($bookModel->getAll() as $book) {
    if (!empty($book['genre'])) {
        $genres[] = $book['genre'];
    }
    if (!empty($book['year'])) {
        $years[] = $book['year'];
    }
}
$uniqueGenres = count(array_unique($genres));
$avgYear = count($years) > 0 ? round(array_sum($years) / count($years)) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Manager Pro - Mi Biblioteca</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📚 Book Manager Pro</h1>
            <div class="header-actions">
                <a href="add.php" class="btn btn-primary">➕ Agregar Libro</a>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total de Libros</h3>
                <div class="value"><?= $totalBooks ?></div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);">
                <h3>Géneros Únicos</h3>
                <div class="value"><?= $uniqueGenres ?></div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <h3>Año Promedio</h3>
                <div class="value"><?= $avgYear > 0 ? $avgYear : 'N/A' ?></div>
            </div>
        </div>

        <!-- Barra de búsqueda -->
        <div class="search-bar">
            <form method="GET" action="" style="margin: 0;">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Buscar por título, autor o género..." 
                    value="<?= htmlspecialchars($searchTerm) ?>"
                    id="searchInput"
                >
            </form>
        </div>

        <?php if ($searchTerm && count($books) > 0): ?>
            <div class="alert alert-success">
                ✓ Se encontraron <?= count($books) ?> libro(s) con el término "<?= htmlspecialchars($searchTerm) ?>"
            </div>
        <?php elseif ($searchTerm && count($books) === 0): ?>
            <div class="alert alert-warning">
                ⚠ No se encontraron libros con el término "<?= htmlspecialchars($searchTerm) ?>"
                <a href="index.php" style="margin-left: 1rem;" class="btn btn-sm btn-secondary">Ver todos</a>
            </div>
        <?php endif; ?>

        <!-- Grid de libros -->
        <?php if (count($books) > 0): ?>
            <div class="books-grid">
                <?php foreach ($books as $book): ?>
                    <div class="book-card">
                        <h3>
                            📖 <?= htmlspecialchars($book['title']) ?>
                        </h3>
                        <div class="author">
                            👤 <?= htmlspecialchars($book['author']) ?>
                        </div>
                        <div class="meta">
                            <?php if (!empty($book['year'])): ?>
                                <div class="meta-item">
                                    📅 <?= htmlspecialchars($book['year']) ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($book['genre'])): ?>
                                <div class="meta-item">
                                    <span class="badge badge-genre"><?= htmlspecialchars($book['genre']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="actions">
                            <a href="edit.php?id=<?= (int)$book['id'] ?>" class="btn btn-sm btn-secondary">
                                ✏️ Editar
                            </a>
                            <a href="delete.php?id=<?= (int)$book['id'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('¿Estás seguro de eliminar este libro?\n\nTítulo: <?= htmlspecialchars($book['title']) ?>\nAutor: <?= htmlspecialchars($book['author']) ?>')">
                                🗑️ Eliminar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📚</div>
                <h3>No hay libros en tu biblioteca</h3>
                <p>Comienza agregando tu primer libro a la colección</p>
                <a href="add.php" class="btn btn-primary">➕ Agregar Primer Libro</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Auto-submit del formulario de búsqueda después de dejar de escribir
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
        }
    </script>
</body>
</html>
