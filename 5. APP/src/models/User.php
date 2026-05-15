<?php

require_once __DIR__ . '/../config/database.php';

class User
{

    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM `user` WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM `user` WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(string $email, string $username, string $password): int
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            'INSERT INTO `user` (email, username, password) VALUES (?, ?, ?)'
        );
        $stmt->execute([$email, $username, $hash]);
        $userId = (int) $this->db->lastInsertId();

        // Asignar rol 'user' por defecto (id = 1)
        $this->assignRole($userId, 1);

        return $userId;
    }

    public function assignRole(int $userId, int $roleId): void
    {
        $stmt = $this->db->prepare(
            'INSERT IGNORE INTO user_has_role (id_user, id_role) VALUES (?, ?)'
        );
        $stmt->execute([$userId, $roleId]);
    }

    public function getRoles(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT r.role_name FROM role r
             INNER JOIN user_has_role uhr ON r.id = uhr.id_role
             WHERE uhr.id_user = ?'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM `user` WHERE email = ?');
        $stmt->execute([$email]);
        return (int) $stmt->fetchColumn() > 0;
    }
    public function update(int $id, string $username, ?string $avatar): void
    {
        $stmt = $this->db->prepare(
            'UPDATE `user` SET username = ?, avatar = ? WHERE id = ?'
        );
        $stmt->execute([$username, $avatar, $id]);
    }

    public function usernameExists(string $username, ?int $excludeId = null): bool
    {
        $sql  = 'SELECT COUNT(*) FROM `user` WHERE username = ?';
        $params = [$username];
        if ($excludeId) {
            $sql .= ' AND id != ?';
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }
    public function getAll(): array
    {
        $stmt = $this->db->prepare(
            'SELECT u.*, GROUP_CONCAT(r.role_name SEPARATOR ", ") as roles_names
             FROM `user` u
             LEFT JOIN user_has_role uhr ON u.id = uhr.id_user
             LEFT JOIN role r ON uhr.id_role = r.id
             GROUP BY u.id
             ORDER BY u.id ASC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRoleList(): array
    {
        $stmt = $this->db->prepare('SELECT id, role_name FROM role ORDER BY id ASC');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateByMod(int $id, string $username, string $email, array $roleIds): void
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                'UPDATE `user` SET username = ?, email = ? WHERE id = ?'
            );
            $stmt->execute([$username, $email, $id]);

            $delStmt = $this->db->prepare('DELETE FROM user_has_role WHERE id_user = ?');
            $delStmt->execute([$id]);

            $insStmt = $this->db->prepare('INSERT INTO user_has_role (id_user, id_role) VALUES (?, ?)');
            foreach ($roleIds as $roleId) {
                $insStmt->execute([$id, $roleId]);
            }

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM `user` WHERE id = ?');
        $stmt->execute([$id]);
    }
}
