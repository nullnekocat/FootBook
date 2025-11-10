<?php
// controllers/CommentController.php
declare(strict_types=1);

use Models\CommentModel;

require_once __DIR__ . '/../models/CommentModel.php';
require_once __DIR__ . '/../Middleware/auth.php';

use function Auth\current_user;

class CommentController {
    private CommentModel $model;

    public function __construct() {
        $this->model = new CommentModel(); // usa tus SP vía Database
        header('Content-Type: application/json; charset=UTF-8');
    }

    public function comentar(): void
    {
        try {
            $in = $this->json_in();

            // Requeridos según tu tabla/SP
            $required = [
                'post_id','content'
            ];

            foreach ($required as $r) {
                if (!isset($in[$r]) || trim((string)$in[$r]) === '') {
                    $this->json_out(422, ['ok'=>false,'error'=>"Campo requerido: $r"]);
                    return;
                }
            }
            // Normaliza/asegura tipos
            $data = [
                'post_id'      => isset($in['post_id']) ? (int)$in['post_id'] : 0,
                'user_id'      => (int)(current_user() ?? 0),
                'content'         => trim((string)$in['content'])
            ];
            // Mandar a llamar al model
            $created = $this->model->createComment($data);
            $commentId = (int)($created['id'] ?? 0);

            if ($commentId <= 0) {
                throw new \RuntimeException('No se obtuvo el ID del comentario', 500);
            }

            $this->json_out(201, [
                'ok' => true,
                'comment_id' => $commentId,
                'comment' => $created, // opcional: devuelve todo el registro creado
            ]);
        } catch (\Throwable $e) {
            $code = (int)($e->getCode() ?: 500);
            $this->json_out($code, ['ok'=>false,'error'=>$e->getMessage()]);
        }
    }
    public function index($id = null): void
    {
        try {
            $postId = $id !== null ? (int)$id : (int)($_GET['post_id'] ?? 0);
            if ($postId <= 0) {
                $this->json(['ok' => false, 'error' => 'post_id inválido'], 422);
                return;
            }

            $rows = $this->model->getComments($postId);

            $this->json([
                'ok'      => true,
                'post_id' => $postId,
                'total'   => count($rows),
                'data'    => $rows,
            ], 200);

        } catch (\Throwable $e) {
            $code = (int)($e->getCode() ?: 500);
            $this->json(['ok' => false, 'error' => $e->getMessage()], $code);
        }
    }


    /* ----------------- helpers ----------------- */
    private function json($data, int $code = 200): void
    {
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    private function json_in(): array
    {
        // Acepta JSON y/x-www-form-urlencoded por compat
        $in = $_POST;
        if (empty($in)) {
            $raw = file_get_contents('php://input');
            if ($raw !== false && $raw !== '') {
                $j = json_decode($raw, true);
                if (is_array($j)) $in = $j;
            }
        }
        return $in ?: [];
    }
    private function json_out(int $code, array $payload): void
    {
        http_response_code($code);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
}