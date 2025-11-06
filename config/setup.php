<?php
// Crea la carpeta de datos si no existe
function ensureDirectories(string $dataDir): void {
    if (!is_dir($dataDir)) {
        // 0755 es suficiente para lectura del servidor web local
        mkdir($dataDir, 0755, true);
    }
}

// Establece conexión a la base de datos SQLite y configura manejo de errores
function createDatabase(string $dbFile): PDO {
    $pdo = new PDO("sqlite:" . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

// Crea las tablas: books (libros) y users (usuarios)
function createTables(PDO $pdo): void {
    // Tabla books: almacena información de libros
    $pdo->exec("CREATE TABLE IF NOT EXISTS books (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        author TEXT NOT NULL,
        year INTEGER,
        genre TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Tabla users: almacena usuarios con contraseña hasheada
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        email TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
}

// Inserta un usuario administrador por defecto (admin / clave123)
function seedAdmin(PDO $pdo): void {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $exists = (int)$stmt->fetchColumn();

    // Solo crea el admin si no existe
    if ($exists === 0) {
        $hash = password_hash("clave123", PASSWORD_DEFAULT);
        $ins = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $ins->execute(["admin", $hash, "admin@biblioteca.edu"]);
    }
}

// Popula la tabla books con 15 libros de ejemplo (clásicos literarios)
function seedSampleData(PDO $pdo): void {
    $books = [
        ['title' => 'El Quijote', 'author' => 'Miguel de Cervantes', 'year' => 1605, 'genre' => 'Novela de Aventuras'],
        ['title' => '1984', 'author' => 'George Orwell', 'year' => 1949, 'genre' => 'Distopía'],
        ['title' => 'Cien años de soledad', 'author' => 'Gabriel García Márquez', 'year' => 1967, 'genre' => 'Realismo Mágico'],
        ['title' => 'El Gran Gatsby', 'author' => 'F. Scott Fitzgerald', 'year' => 1925, 'genre' => 'Novela Romántica'],
        ['title' => 'Mujercitas', 'author' => 'Louisa May Alcott', 'year' => 1868, 'genre' => 'Drama Familiar'],
        ['title' => 'Dune', 'author' => 'Frank Herbert', 'year' => 1965, 'genre' => 'Ciencia Ficción'],
        ['title' => 'El Hobbit', 'author' => 'J.R.R. Tolkien', 'year' => 1937, 'genre' => 'Fantasía Épica'],
        ['title' => 'Orgullo y Prejuicio', 'author' => 'Jane Austen', 'year' => 1813, 'genre' => 'Romance Clásico'],
        ['title' => 'Crimen y Castigo', 'author' => 'Fiódor Dostoievski', 'year' => 1866, 'genre' => 'Psicológica'],
        ['title' => 'La Revolución Francesa', 'author' => 'Charles Dickens', 'year' => 1859, 'genre' => 'Novela Histórica'],
        ['title' => 'Frankenstein', 'author' => 'Mary Shelley', 'year' => 1818, 'genre' => 'Terror Gótico'],
        ['title' => 'El Código Da Vinci', 'author' => 'Dan Brown', 'year' => 2003, 'genre' => 'Misterio Thriller'],
        ['title' => 'Metamorfosis', 'author' => 'Franz Kafka', 'year' => 1915, 'genre' => 'Novela Existencial'],
        ['title' => 'La Bruja de Portobello', 'author' => 'Paulo Coelho', 'year' => 2006, 'genre' => 'Espiritualidad'],
        ['title' => 'El Nombre del Viento', 'author' => 'Patrick Rothfuss', 'year' => 2007, 'genre' => 'Fantasía Contemporánea'],
    ];

    try {
        $stmt = $pdo->prepare('INSERT INTO books (title, author, year, genre) VALUES (?, ?, ?, ?)');
        
        foreach ($books as $book) {
            $stmt->execute([
                $book['title'],
                $book['author'],
                $book['year'],
                $book['genre']
            ]);
        }
    } catch (PDOException $e) {
        // Registra errores sin detener la ejecución
        error_log("Error seeding sample data: " . $e->getMessage());
    }
}
