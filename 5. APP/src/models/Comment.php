<?php

require_once __DIR__ . '/../config/database.php';

class Comment
{

    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getByPublication(int $publicationId): array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.username, u.avatar
            FROM comment c
            INNER JOIN user u ON c.id_user = u.id
            WHERE c.id_publication = ?
            ORDER BY c.date_creation ASC
        ");
        $stmt->execute([$publicationId]);
        return $stmt->fetchAll();
    }

    public function create(int $publicationId, int $userId, string $content, ?int $parentId = null): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO comment (id_publication, id_user, id_comment_parent, content) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$publicationId, $userId, $parentId, $content]);
        return (int) $this->db->lastInsertId();
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM comment WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare('SELECT * FROM comment WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAll(int $limit, int $offset): array
    {
        $stmt = $this->db->prepare("
        SELECT c.*, u.username, u.avatar
        FROM comment c
        INNER JOIN user u ON c.id_user = u.id
        ORDER BY c.date_creation DESC
        LIMIT :limit OFFSET :offset
    ");
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAll(): int
    {
        return (int) $this->db->query('SELECT COUNT(*) FROM comment')->fetchColumn();
    }

    public function search(string $query): array
    {
        $stmt = $this->db->prepare("
        SELECT c.*, u.username, u.avatar
        FROM comment c
        INNER JOIN user u ON c.id_user = u.id
        WHERE c.content LIKE ?
        ORDER BY c.date_creation DESC
        LIMIT 30
    ");
        $stmt->execute(['%' . $query . '%']);
        return $stmt->fetchAll();
    }

    public function saveImages(int $commentId, array $files): void
    {
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $dir     = __DIR__ . '/../assets/img/comments/';
        if (!is_dir($dir)) mkdir($dir, 0775, true);

        $count = 0;
        foreach ($files['tmp_name'] as $i => $tmp) {
            if ($count >= 3 || empty($tmp)) continue;
            $mime = mime_content_type($tmp);
            if (!in_array($mime, $allowed)) continue;
            $ext      = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $filename = 'comment_' . $commentId . '_' . $i . '_' . time() . '.' . $ext;
            move_uploaded_file($tmp, $dir . $filename);
            $this->db->prepare(
                'INSERT INTO comment_image (id_comment, path) VALUES (?, ?)'
            )->execute([$commentId, '/assets/img/comments/' . $filename]);
            $count++;
        }
    }

    public function getImages(int $commentId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM comment_image WHERE id_comment = ?'
        );
        $stmt->execute([$commentId]);
        return $stmt->fetchAll();
    }
}
