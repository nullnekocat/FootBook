<?php
declare(strict_types=1);

use Models\CategoryModel;
require_once __DIR__ . '/../models/CategoryModel.php';

class CategoryController
{
    private CategoryModel $model;

    public function __construct()
    {
        $this->model = new CategoryModel();
        header('Content-Type: application/json; charset=UTF-8');
    }

    private function json($data, int $code = 200): void
    {
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /** GET /FootBook/api/categories */
    public function list(): void
    {
        try {
            $rows = $this->model->getListOfCategory();   // <- tu SP
            $this->json(['ok' => true, 'data' => $rows]);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /** POST /FootBook/api/categories/:name */
    public function create($name): void
    {
        try {
            $name = trim((string)$name);
            if ($name === '') {
                $this->json(['ok' => false, 'error' => 'name is required'], 422);
                return;
            }

            // tu SP: no necesitas body, el name viene en la URL
            $this->model->createCategory(['name' => $name]);

            $this->json(['ok' => true, 'data' => ['name' => $name]], 201);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
