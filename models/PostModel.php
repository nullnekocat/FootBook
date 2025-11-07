<?php
// models/PostModel.php
namespace Models;
use PDO;
use Database;
use PDOException;
use RuntimeException;

require_once __DIR__ . '/../core/Database.php';

class PostModel {
    private Database $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Crear una publicación
    public function sp_create_post($data) {
        try {
            $params = [
                $data['user_id'],
                $data['category_id'],
                $data['worldcup_id'],
                $data['team'] ?? null,
                $data['title'],
                $data['description'],
                $data['media'] ?? null
            ];
            $types = [
                PDO::PARAM_INT, //user_id
                PDO::PARAM_INT, //category_id
                PDO::PARAM_INT, //worldcup_id
                PDO::PARAM_STR, //team
                PDO::PARAM_STR, //title
                PDO::PARAM_STR, //description
                $params[6] === null ? PDO::PARAM_NULL : PDO::PARAM_LOB //media
            ];
            $stmt = $this->db->callSP('sp_create_post', $params, $types);
            $postId = 0;
            // Algunos drivers generan múltiples result sets con CALL; avanza hasta hallar post_id
            do {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row && isset($row['post_id'])) {
                    $postId = (int)$row['post_id'];
                    break;
                }
            } while ($stmt->nextRowset());

            $stmt->closeCursor();

            if ($postId <= 0) {
                throw new RuntimeException('No se pudo obtener el post_id devuelto por el SP.', 500);
            }

            return $postId;          
        } catch (PDOException $e) {
            $msg = $e->getMessage();
            throw new RuntimeException('Error al crear publicación: ' . $msg, 400);
        }
    }
    // Obtener publicaciones pendientes de aprobación
    public function get_posts_to_approved(): array {
        $stmt = $this->db->callSP('sp_get_posts_to_approved'); 
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows ?: [];
    }

    // Aprobar o Rechazar publicación
    public function approve_post(array $data): array
    {
        try {
            $params = [
                (int)$data['post_id'],
                (int)$data['is_approved'], // 0 o 1
            ];
            $types = [
                PDO::PARAM_INT, // post_id
                PDO::PARAM_INT, // is_approved (TINYINT -> usa INT en PDO)
            ];

            // Llamar al SP correcto
            $stmt = $this->db->callSP('sp_approve_post', $params, $types);

            // El SP devuelve: id, approved, approved_at
            $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
            $stmt->closeCursor();

            if (!$row) {
                throw new RuntimeException('Sin datos devueltos por sp_approve_post', 500);
            }

            return $row;

        } catch (PDOException $e) {
            throw new RuntimeException('Error al aprobar/desaprobar post: ' . $e->getMessage(), 400);
        }
    }

    public function get_feed(
        int $userId,
        int $afterId,
        int $limit,
        ?int $categoryId = null,
        ?int $worldcupId = null
    ): array {
        $params = [$userId, $afterId, $limit, $categoryId, $worldcupId];
        $types  = [
            PDO::PARAM_INT, // userId
            PDO::PARAM_INT, // afterId
            PDO::PARAM_INT, // limit
            is_null($categoryId) ? PDO::PARAM_NULL : PDO::PARAM_INT,
            is_null($worldcupId) ? PDO::PARAM_NULL : PDO::PARAM_INT,
        ];

        $stmt = $this->db->callSP('sp_get_feed', $params, $types);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows ?: [];
    }
}