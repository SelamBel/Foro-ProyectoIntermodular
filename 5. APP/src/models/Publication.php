<?php

require_once __DIR__ . '/../config/database.php';

class Publication {

    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll(string $order = 'votes', int $limit = 10, int $offset = 0): array {
        $orderSql = match($order) {
            'newest' => 'p.date_creation DESC',
            'oldest' => 'p.date_creation ASC',
            default  => 'votes DESC'
        };

        $stmt = $this->db->prepare("
            SELECT p.*,
                   u.name, u.surname,
                   COALESCE(SUM(CASE WHEN v.type = 1 THEN 1 ELSE 0 END), 0) AS upvotes,
                   COALESCE(SUM(CASE WHEN v.type = 0 THEN 1 ELSE 0 END), 0) AS downvotes,
                   COALESCE(SUM(CASE WHEN v.type = 1 THEN 1 WHEN v.type = 0 THEN -1 ELSE 0 END), 0) AS votes,
                   COUNT(DISTINCT c.id) AS comment_count
            FROM publication p
            INNER JOIN user u ON p.id_user = u.id
            LEFT JOIN vote v ON v.id_publication = p.id
            LEFT JOIN comment c ON c.id_publication = p.id
            GROUP BY p.id
            ORDER BY $orderSql
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false {
        $stmt = $this->db->prepare("
            SELECT p.*,
                   u.name, u.surname,
                   COALESCE(SUM(CASE WHEN v.type = 1 THEN 1 ELSE 0 END), 0) AS upvotes,
                   COALESCE(SUM(CASE WHEN v.type = 0 THEN 1 ELSE 0 END), 0) AS downvotes,
                   COUNT(DISTINCT c.id) AS comment_count
            FROM publication p
            INNER JOIN user u ON p.id_user = u.id
            LEFT JOIN vote v ON v.id_publication = p.id
            LEFT JOIN comment c ON c.id_publication = p.id
            WHERE p.id = ?
            GROUP BY p.id
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(int $userId, string $title, string $content): int {
        $stmt = $this->db->prepare(
            'INSERT INTO publication (id_user, title, content) VALUES (?, ?, ?)'
        );
        $stmt->execute([$userId, $title, $content]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $title, string $content): void {
        $stmt = $this->db->prepare(
            'UPDATE publication SET title = ?, content = ?, date_edited = NOW() WHERE id = ?'
        );
        $stmt->execute([$title, $content, $id]);
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare('DELETE FROM publication WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function countAll(): int {
        return (int) $this->db->query('SELECT COUNT(*) FROM publication')->fetchColumn();
    }

    public function getUserVote(int $userId, int $publicationId): int|null {
        $stmt = $this->db->prepare(
            'SELECT type FROM vote WHERE id_user = ? AND id_publication = ?'
        );
        $stmt->execute([$userId, $publicationId]);
        $row = $stmt->fetch();
        return $row ? (int) $row['type'] : null;
    }

    public function getByUser(int $userId): array {
    $stmt = $this->db->prepare("
        SELECT p.*,
               COALESCE(SUM(CASE WHEN v.type = 1 THEN 1 ELSE 0 END), 0) AS upvotes,
               COALESCE(SUM(CASE WHEN v.type = 0 THEN 1 ELSE 0 END), 0) AS downvotes,
               COUNT(DISTINCT c.id) AS comment_count
        FROM publication p
        LEFT JOIN vote v ON v.id_publication = p.id
        LEFT JOIN comment c ON c.id_publication = p.id
        WHERE p.id_user = ?
        GROUP BY p.id
        ORDER BY p.date_creation DESC
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}
}