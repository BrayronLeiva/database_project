<?php
function ensureDirectories(string $dataDir): void {
    if (!is_dir($dataDir)) {
        // 0755 es suficiente para lectura del servidor web local
        mkdir($dataDir, 0755, true);
    }
}

function createDatabase(string $dbFile): PDO {
    $pdo = new PDO("sqlite:" . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function createTables(PDO $pdo): void {
    // Tabla books
    $pdo->exec("CREATE TABLE IF NOT EXISTS books (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        author TEXT NOT NULL,
        year INTEGER,
        genre TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Tabla users
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        email TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
}

function seedAdmin(PDO $pdo): void {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $exists = (int)$stmt->fetchColumn();

    if ($exists === 0) {
        $hash = password_hash("clave123", PASSWORD_DEFAULT);
        $ins = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $ins->execute(["admin", $hash, "admin@biblioteca.edu"]);
    }
}

function seedSampleData(PDO $pdo): void {
    $count = (int)$pdo->query("SELECT COUNT(*) FROM books")->fetchColumn();
    if ($count === 0) {
        $ins = $pdo->prepare("INSERT INTO books (title, author, year, genre) VALUES (?, ?, ?, ?)");
        $ins->execute(["Cien Años de Soledad", "Gabriel García Márquez", 1967, "Novela"]);
        $ins->execute(["El señor de los anillos", "J.R.R. Tolkien", 1954, "Fantasía"]);
    }
}
