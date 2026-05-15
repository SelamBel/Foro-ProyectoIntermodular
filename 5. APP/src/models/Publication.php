<?php

require_once __DIR__ . '/../config/database.php';

class Publication
{

    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll(string $order = 'votes', int $limit = 10, int $offset = 0): array
    {
        $orderSql = match ($order) {
            'newest' => 'p.date_creation DESC',
            'oldest' => 'p.date_creation ASC',
            default  => 'votes DESC'
        };

        $stmt = $this->db->prepare("
            SELECT p.*,
                   u.username,
                   (SELECT COUNT(*) FROM vote v WHERE v.id_publication = p.id AND v.type = 1) AS upvotes,
                   (SELECT COUNT(*) FROM vote v WHERE v.id_publication = p.id AND v.type = 0) AS downvotes,
                   (SELECT COALESCE(SUM(CASE WHEN v2.type = 1 THEN 1 ELSE -1 END), 0) FROM vote v2 WHERE v2.id_publication = p.id) AS votes,
                   (SELECT COUNT(*) FROM comment c WHERE c.id_publication = p.id) AS comment_count
            FROM publication p
            INNER JOIN user u ON p.id_user = u.id
            ORDER BY $orderSql
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("
            SELECT p.*,
                   u.username,
                   (SELECT COUNT(*) FROM vote v WHERE v.id_publication = p.id AND v.type = 1) AS upvotes,
                   (SELECT COUNT(*) FROM vote v WHERE v.id_publication = p.id AND v.type = 0) AS downvotes,
                   (SELECT COUNT(*) FROM comment c WHERE c.id_publication = p.id) AS comment_count
            FROM publication p
            INNER JOIN user u ON p.id_user = u.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create(int $userId, string $title, string $content): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO publication (id_user, title, content) VALUES (?, ?, ?)'
        );
        $stmt->execute([$userId, $title, $content]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $title, string $content): void
    {
        $stmt = $this->db->prepare(
            'UPDATE publication SET title = ?, content = ?, date_edited = NOW() WHERE id = ?'
        );
        $stmt->execute([$title, $content, $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM publication WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM publication')->fetchColumn();
    }

    public function getUserVote(int $userId, int $publicationId): int|null
    {
        $stmt = $this->db->prepare(
            'SELECT type FROM vote WHERE id_user = ? AND id_publication = ?'
        );
        $stmt->execute([$userId, $publicationId]);
        $row = $stmt->fetch();
        return $row ? (int) $row['type'] : null;
    }

    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*,
                   (SELECT COUNT(*) FROM vote v WHERE v.id_publication = p.id AND v.type = 1) AS upvotes,
                   (SELECT COUNT(*) FROM vote v WHERE v.id_publication = p.id AND v.type = 0) AS downvotes,
                   (SELECT COUNT(*) FROM comment c WHERE c.id_publication = p.id) AS comment_count
            FROM publication p
            WHERE p.id_user = ?
            ORDER BY p.date_creation DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getAllFiltered(string $order = 'newest', string $search = ''): array
    {
        $orderSql = match ($order) {
            'votes'  => 'votes DESC',
            'oldest' => 'p.date_creation ASC',
            default  => 'p.date_creation DESC'
        };

        $where = $search ? "WHERE p.title LIKE :search OR p.content LIKE :search2" : '';

        $stmt = $this->db->prepare("
            SELECT p.*,
                   u.username,
                   (SELECT COUNT(*) FROM vote v WHERE v.id_publication = p.id AND v.type = 1) AS upvotes,
                   (SELECT COUNT(*) FROM vote v WHERE v.id_publication = p.id AND v.type = 0) AS downvotes,
                   (SELECT COALESCE(SUM(CASE WHEN v2.type = 1 THEN 1 ELSE -1 END), 0) FROM vote v2 WHERE v2.id_publication = p.id) AS votes,
                   (SELECT COUNT(*) FROM comment c WHERE c.id_publication = p.id) AS comment_count
            FROM publication p
            INNER JOIN user u ON p.id_user = u.id
            $where
            ORDER BY $orderSql
        ");

        if ($search) {
            $like = '%' . $search . '%';
            $stmt->bindValue(':search',  $like);
            $stmt->bindValue(':search2', $like);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }
}
