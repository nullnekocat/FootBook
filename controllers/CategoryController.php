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

    public function index(): void { // listar categorías
        try {
            $rows = $this->model->getListOfCategory();
            $this->json(200, ['data' => $rows]);
        } catch (Throwable $e) {
            $this->json(500, ['error' => 'Server error', 'detail' => $e->getMessage()]);
        }
    }

    public function store(): void { // crear nueva categoría
        try {
            $body = $this->jsonIn();
            $name = trim((string)($body['name'] ?? ''));

            if ($name === '') {
                $this->json(422, ['error' => 'name is required']);
                return;
            }
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

/* ===== Dispatcher local, igual estilo que CategoryController ===== */
$controller = new CategoryController();
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

// recorta base path (p.ej., /FootBook) de forma case-insensitive
$base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
if ($base && stripos($uriPath, $base) === 0) {
    $uriPath = substr($uriPath, strlen($base));
}
$path = '/' . trim($uriPath, '/');       // '/api/login'
$low  = strtolower($path);

if ($method === 'GET' && $low === '/api/categories/list')    { $controller->index();    exit; }
if ($method === 'POST' && $low === '/api/categories/create')    { $controller->store();    exit; }

http_response_code(404);
echo json_encode(['error' => 'Ruta no encontrada']);
exit;
