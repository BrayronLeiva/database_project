<?php
class Book {
    private PDO $pdo;

    // Constructor: recibe la conexión PDO y la almacena para usarla en todos los métodos
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Obtiene todos los libros ordenados por fecha (más recientes primero)
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM books ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un libro específico por su ID, retorna null si no existe
    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Obtiene libros paginados (10 por página por defecto)
    // Retorna: libros, total, número de páginas y página actual
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

    // Busca libros por palabra clave en título o autor
    public function search(string $query): array {
        $searchTerm = "%{$query}%";
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY created_at DESC");
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca en un campo específico (título o autor)
    // Valida el campo para evitar inyecciones SQL (whitelist)
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

    // Inserta un nuevo libro en la base de datos
    public function add(string $title, string $author, ?int $year, ?string $genre): bool {
        $stmt = $this->pdo->prepare("INSERT INTO books (title, author, year, genre) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $author, $year, $genre]);
    }

    // Actualiza los datos de un libro existente por su ID
    public function update(int $id, string $title, string $author, ?int $year, ?string $genre): bool {
        $stmt = $this->pdo->prepare("UPDATE books SET title = ?, author = ?, year = ?, genre = ? WHERE id = ?");
        return $stmt->execute([$title, $author, $year, $genre, $id]);
    }

    // Elimina un libro de la base de datos por su ID
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
