<?php
// controllers/LikeController.php
declare(strict_types=1);

use Models\LikeModel;

require_once __DIR__ . '/../models/LikeModel.php';
require_once __DIR__ . '/../Middleware/auth.php';

use function Auth\current_user;

class LikeController {
    private LikeModel $model;

    public function __construct() {
        $this->model = new LikeModel();
        header('Content-Type: application/json; charset=UTF-8');
    }

    /**
     * POST /FootBook/api/posts/:id/like
     * Toggle like en un post
     */
    public function toggleLike($id = null): void
    {
        try {
            $userId = (int)(current_user() ?? 0);
            
            if ($userId <= 0) {
                $this->json(['ok' => false, 'error' => 'No autenticado'], 401);
                return;
            }

            // El ID viene del parámetro de ruta
            $postId = $id ? (int)$id : 0;

            if ($postId <= 0) {
                $this->json(['ok' => false, 'error' => 'post_id inválido'], 422);
                return;
            }

            $result = $this->model->togglePostLike($userId, $postId);

            $this->json([
                'ok' => true,
                'liked' => (int)($result['status'] ?? 0) === 1,
                'total_likes' => (int)($result['total_likes'] ?? 0),
                'data' => $result
            ]);

        } catch (\Throwable $e) {
            $code = (int)($e->getCode() ?: 500);
            $this->json(['ok' => false, 'error' => $e->getMessage()], $code);
        }
    }

    private function json($data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}