<?php
class User {
    private PDO $pdo;

    // Constructor: recibe la conexión PDO y la almacena para usarla en todos los métodos
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Obtiene todos los usuarios sin mostrar contraseñas (por seguridad)
    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un usuario específico por su ID, sin mostrar la contraseña
    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Obtiene un usuario por nombre de usuario (incluye contraseña hasheada para verificación en login)
    public function getByUsername(string $username): ?array {
        $stmt = $this->pdo->prepare("SELECT id, username, password, email, created_at FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Inserta un nuevo usuario con contraseña hasheada
    public function add(string $username, string $password, ?string $email): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hashedPassword, $email]);
    }

    // Actualiza nombre de usuario y email (sin tocar la contraseña)
    public function update(int $id, string $username, ?string $email): bool {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $id]);
    }

    // Actualiza la contraseña de un usuario (hasheada)
    public function updatePassword(int $id, string $newPassword): bool {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    // Elimina un usuario de la base de datos por su ID
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Verifica si una contraseña en texto plano coincide con su versión hasheada
    public function verifyPassword(string $plainPassword, string $hashedPassword): bool {
        return password_verify($plainPassword, $hashedPassword);
    }
}