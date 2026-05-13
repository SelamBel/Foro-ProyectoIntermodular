<?php

require_once __DIR__ . '/../config/database.php';

class Comment {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getByPublication(int $publicationId): array {
        $stmt = $this->db->prepare("
            SELECT c.*, u.name, u.surname
            FROM comment c
            INNER JOIN user u ON c.id_user = u.id
            WHERE c.id_publication = ?
            ORDER BY c.date_creation ASC
        ");
        $stmt->execute([$publicationId]);
        return $stmt->fetchAll();
    }

    public function create(int $publicationId, int $userId, string $content, ?int $parentId = null): int {
        $stmt = $this->db->prepare(
            'INSERT INTO comment (id_publication, id_user, id_comment_parent, content) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$publicationId, $userId, $parentId, $content]);
        return (int) $this->db->lastInsertId();
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare('DELETE FROM comment WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare('SELECT * FROM comment WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}