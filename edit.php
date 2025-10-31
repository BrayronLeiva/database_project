<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Book.php';

$bookModel = new Book($pdo);

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $year   = $_POST['year'] ?? null;
    $genre  = $_POST['genre'] ?? '';

    if ($title && $author) {
        $bookModel->update($id, $title, $author, $year, $genre);
        header("Location: index.php");
        exit;
    } else {
        $error = "Título y autor son obligatorios.";
    }
}

// Obtener datos actuales
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die("Libro no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Editar Libro</title></head>
<body>
    <h1>✏️ Editar Libro</h1>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <label>Título: <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required></label><br>
        <label>Autor: <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required></label><br>
        <label>Año: <input type="number" name="year" value="<?= htmlspecialchars($book['year']) ?>"></label><br>
        <label>Género: <input type="text" name="genre" value="<?= htmlspecialchars($book['genre']) ?>"></label><br>
        <button type="submit">Actualizar</button>
    </form>
    <p><a href="index.php">⬅️ Volver</a></p>
</body>
</html>
