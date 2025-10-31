<?php
class Book {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM books ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add(string $title, string $author, ?int $year, ?string $genre): bool {
        $stmt = $this->pdo->prepare("INSERT INTO books (title, author, year, genre) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $author, $year, $genre]);
    }

    public function update(int $id, string $title, string $author, ?int $year, ?string $genre): bool {
        $stmt = $this->pdo->prepare("UPDATE books SET title = ?, author = ?, year = ?, genre = ? WHERE id = ?");
        return $stmt->execute([$title, $author, $year, $genre, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
