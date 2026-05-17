<?php

require_once __DIR__ . '/../config/database.php';

class Notification
{

    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function create(int $userId, string $type, string $message, ?string $url = null): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO notification (id_user, type, message, url) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $type, $message, $url]);
    }

    public function getByUser(int $userId, int $limit = 20): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM notification WHERE id_user = ? ORDER BY date_creation DESC LIMIT ?'
        );
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countUnread(int $userId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM notification WHERE id_user = ? AND is_read = 0'
        );
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public function markAllRead(int $userId): void
    {
        $stmt = $this->db->prepare(
            'UPDATE notification SET is_read = 1 WHERE id_user = ?'
        );
        $stmt->execute([$userId]);
    }

    public function markRead(int $id): void
    {
        $stmt = $this->db->prepare(
            'UPDATE notification SET is_read = 1 WHERE id = ?'
        );
        $stmt->execute([$id]);
    }

    public function delete(int $id, int $userId): void
    {
        $stmt = $this->db->prepare(
            'DELETE FROM notification WHERE id = ? AND id_user = ?'
        );
        $stmt->execute([$id, $userId]);
    }

    public function deleteAll(int $userId): void
    {
        $stmt = $this->db->prepare('DELETE FROM notification WHERE id_user = ?');
        $stmt->execute([$userId]);
    }
}
