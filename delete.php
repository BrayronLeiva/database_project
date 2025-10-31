<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Book.php';

$bookModel = new Book($pdo);

$id = $_GET['id'] ?? null;
if ($id) {
    $bookModel->delete($id);
}
header("Location: index.php");
exit;
