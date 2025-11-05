<?php
class User {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByUsername(string $username): ?array {
        $stmt = $this->pdo->prepare("SELECT id, username, password, email, created_at FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function add(string $username, string $password, ?string $email): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $hashedPassword, $email]);
    }

    public function update(int $id, string $username, ?string $email): bool {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        return $stmt->execute([$username, $email, $id]);
    }

    public function updatePassword(int $id, string $newPassword): bool {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function verifyPassword(string $plainPassword, string $hashedPassword): bool {
        return password_verify($plainPassword, $hashedPassword);
    }
}