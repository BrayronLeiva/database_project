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
$books = $bookModel->getAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Book Manager</title>
</head>
<body>
    <h1>📚 Lista de Libros</h1>
    <a href="add.php">➕ Agregar Libro</a>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th><th>Título</th><th>Autor</th><th>Año</th><th>Género</th><th>Acciones</th>
        </tr>
        <?php foreach ($books as $b): ?>
        <tr>
            <td><?= htmlspecialchars($b['id']) ?></td>
            <td><?= htmlspecialchars($b['title']) ?></td>
            <td><?= htmlspecialchars($b['author']) ?></td>
            <td><?= htmlspecialchars($b['year']) ?></td>
            <td><?= htmlspecialchars($b['genre']) ?></td>
            <td>
                <a href="edit.php?id=<?= (int)$b['id'] ?>">✏️ Editar</a> | 
                <a href="delete.php?id=<?= (int)$b['id'] ?>" onclick="return confirm('¿Eliminar este libro?')">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
