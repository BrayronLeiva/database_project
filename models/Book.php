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

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getPaginated(int $page = 1, int $perPage = 10): array {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->pdo->prepare("SELECT * FROM books ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->execute([$perPage, $offset]);
        
        $totalStmt = $this->pdo->query("SELECT COUNT(*) FROM books");
        $total = $totalStmt->fetchColumn();
        
        return [
            'books' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'pages' => ceil($total / $perPage),
            'current_page' => $page
        ];
    }

    public function search(string $query): array {
        $searchTerm = "%{$query}%";
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY created_at DESC");
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchByField(string $query, string $field = 'title'): array {
        $searchTerm = "%{$query}%";
        $allowedFields = ['title', 'author'];
        
        // Validar que el campo sea seguro
        if (!in_array($field, $allowedFields)) {
            $field = 'title';
        }
        
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE {$field} LIKE ? ORDER BY created_at DESC");
        $stmt->execute([$searchTerm]);
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
