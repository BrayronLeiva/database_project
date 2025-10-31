<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Book.php';

$bookModel = new Book($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $year   = $_POST['year'] ?? null;
    $genre  = $_POST['genre'] ?? '';

    if ($title && $author) {
        $bookModel->add($title, $author, $year, $genre);
        header("Location: index.php");
        exit;
    } else {
        $error = "Título y autor son obligatorios.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Agregar Libro</title></head>
<body>
    <h1>➕ Agregar Libro</h1>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <label>Título: <input type="text" name="title" required></label><br>
        <label>Autor: <input type="text" name="author" required></label><br>
        <label>Año: <input type="number" name="year"></label><br>
        <label>Género: <input type="text" name="genre"></label><br>
        <button type="submit">Guardar</button>
    </form>
    <p><a href="index.php">⬅️ Volver</a></p>
</body>
</html>
