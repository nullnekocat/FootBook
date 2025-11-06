<?php
// controllers/WorldCupApi.php
use Models\WorldCupModel;
require_once __DIR__ . '/../models/WorldCupModel.php';

class WorldCupApi
{
    private WorldCupModel $model;

    public function __construct()
    {
        $this->model = new WorldCupModel();
        header('Content-Type: application/json; charset=utf-8');
    }

    private function json($data, int $code = 200): void
    {
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /** GET /FootBook/api/worldcups
     *  Devuelve [{ id, name, country, year, description, banner_b64 }, ...]
     */
    public function index(): void
    {
        try {
            $rows = $this->model->getAllWorldCups();
            $this->json(['ok' => true, 'data' => $rows]);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /** GET /FootBook/api/worldcups/:id
     *  Reusa la lista y devuelve solo 1
     */
    public function show($id): void
    {
        try {
            $id = (int)$id;
            $rows = $this->model->getWorldCupById($id);
            $found = null;
            foreach ($rows as $r) {
                // soporta distintas claves de id si fuera el caso
                $rid = $r['id'] ?? $r['worldcup_id'] ?? $r['id_worldcup'] ?? null;
                if ((int)$rid === $id) { $found = $r; break; }
            }

            if (!$found) { $this->json(['ok'=>false,'error'=>'Not found'], 404); return; }
            $this->json(['ok' => true, 'data' => $found]);
        } catch (\Throwable $e) {
            $this->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
