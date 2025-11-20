<?php
// models/LikeModel.php
namespace Models;
use PDO;
use Database;
use RuntimeException;

require_once __DIR__ . '/../core/Database.php';

class LikeModel {
    private Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function togglePostLike(int $userId, int $postId): array
    {
        try {
            $params = [$userId, $postId];
            $types = [PDO::PARAM_INT, PDO::PARAM_INT];

            $stmt = $this->db->callSP('sp_toggle_post_like', $params, $types);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->db->finish($stmt);

            if (!$row) {
                throw new RuntimeException('No se obtuvo respuesta del procedimiento', 500);
            }

            return $row;

        } catch (\PDOException $e) {
            throw new RuntimeException('Error al procesar like: ' . $e->getMessage(), 400);
        }
    }
}