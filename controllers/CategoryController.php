<?php
declare(strict_types=1);

use Models\CategoryModel;
require_once __DIR__ . '/../models/CategoryModel.php';

header('Content-Type: application/json; charset=UTF-8');

class CategoryController {
    private CategoryModel $model;

    public function __construct() {
        $this->model = new CategoryModel(); // usa tus SP vía Database
    }

    public function index(): void {
        try {
            $rows = $this->model->getListOfCategory();
            $this->json(200, ['data' => $rows]);
        } catch (Throwable $e) {
            $this->json(500, ['error' => 'Server error', 'detail' => $e->getMessage()]);
        }
    }

    public function store(): void {
        try {
            $body = $this->jsonIn();
            $name = trim((string)($body['name'] ?? ''));

            if ($name === '') {
                $this->json(422, ['error' => 'name is required']);
                return;
            }

            // Tu model devuelve ['category_id' => null] por ahora; lo respetamos
            $result = $this->model->createCategory(['name' => $name]);

            $this->json(201, [
                'ok'           => true,
                'message'      => 'Category created',
                'category_id'  => $result['category_id'] ?? null
            ]);
        } catch (Throwable $e) {
            $this->json(500, ['error' => 'Server error', 'detail' => $e->getMessage()]);
        }
    }

    /* ----------------- helpers ----------------- */

    private function jsonIn(): array {
        $raw = file_get_contents('php://input') ?: '';
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private function json(int $status, array $data): void {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$controller = new CategoryController();
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

switch ($method) {
    case 'GET':
        $controller->index();
        break;
    case 'POST':
        $controller->store();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed'], JSON_UNESCAPED_UNICODE);
        exit;
}
