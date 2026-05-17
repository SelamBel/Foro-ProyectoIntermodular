<?php

require_once __DIR__ . '/../config/database.php';

class Message {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getConversations(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT
                m.*,
                u.username,
                u.avatar,
                (SELECT COUNT(*) FROM message m2
                 WHERE m2.id_sender = other_user.id
                 AND m2.id_receiver = :uid2
                 AND m2.is_read = 0) AS unread_count
            FROM message m
            INNER JOIN (
                SELECT
                    CASE WHEN id_sender = :uid3 THEN id_receiver ELSE id_sender END AS other_id,
                    MAX(id) AS last_id
                FROM message
                WHERE id_sender = :uid4 OR id_receiver = :uid5
                GROUP BY other_id
            ) conv ON m.id = conv.last_id
            INNER JOIN user other_user ON other_user.id = conv.other_id
            INNER JOIN user u ON u.id = conv.other_id
            ORDER BY m.date_creation DESC
        ");
        $stmt->bindValue(':uid2', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':uid3', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':uid4', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':uid5', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getConversationWith(int $userId, int $otherId): array {
        $stmt = $this->db->prepare("
            SELECT m.*, u.username, u.avatar
            FROM message m
            INNER JOIN user u ON u.id = m.id_sender
            WHERE (m.id_sender = :a AND m.id_receiver = :b)
               OR (m.id_sender = :c AND m.id_receiver = :d)
            ORDER BY m.date_creation ASC
        ");
        $stmt->bindValue(':a', $userId,  PDO::PARAM_INT);
        $stmt->bindValue(':b', $otherId, PDO::PARAM_INT);
        $stmt->bindValue(':c', $otherId, PDO::PARAM_INT);
        $stmt->bindValue(':d', $userId,  PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function send(int $senderId, int $receiverId, string $content): int {
        $stmt = $this->db->prepare(
            'INSERT INTO message (id_sender, id_receiver, content) VALUES (?, ?, ?)'
        );
        $stmt->execute([$senderId, $receiverId, $content]);
        return (int) $this->db->lastInsertId();
    }

    public function markReadFrom(int $senderId, int $receiverId): void {
        $stmt = $this->db->prepare(
            'UPDATE message SET is_read = 1 WHERE id_sender = ? AND id_receiver = ? AND is_read = 0'
        );
        $stmt->execute([$senderId, $receiverId]);
    }

    public function countUnread(int $userId): int {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM message WHERE id_receiver = ? AND is_read = 0'
        );
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }
}