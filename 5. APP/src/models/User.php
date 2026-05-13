<?php

require_once __DIR__ . '/../config/database.php';

class User {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function findByEmail(string $email): array|false {
        $stmt = $this->db->prepare('SELECT * FROM `user` WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare('SELECT * FROM `user` WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(string $email, string $name, string $surname, string $password): int {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            'INSERT INTO `user` (email, name, surname, password) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$email, $name, $surname, $hash]);
        $userId = (int) $this->db->lastInsertId();

        // Asignar rol 'user' por defecto (id = 1)
        $this->assignRole($userId, 1);

        return $userId;
    }

    public function assignRole(int $userId, int $roleId): void {
        $stmt = $this->db->prepare(
            'INSERT IGNORE INTO user_has_role (id_user, id_role) VALUES (?, ?)'
        );
        $stmt->execute([$userId, $roleId]);
    }

    public function getRoles(int $userId): array {
        $stmt = $this->db->prepare(
            'SELECT r.role_name FROM role r
             INNER JOIN user_has_role uhr ON r.id = uhr.id_role
             WHERE uhr.id_user = ?'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM `user` WHERE email = ?');
        $stmt->execute([$email]);
        return (int) $stmt->fetchColumn() > 0;
    }
}