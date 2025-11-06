<?php
declare(strict_types=1);

use Models\WorldCupModel;

require_once __DIR__ . '/../models/WorldCupModel.php';

header('Content-Type: application/json; charset=UTF-8');

class WorldCupController {
    private WorldCupModel $model;

    public function __construct() {
        $this->model = new WorldCupModel(); 
    }

    public function index(): void {
        try {
            $rows = $this->model->getAllWorldCups();
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['ok' => true, 'data' => $rows], JSON_UNESCAPED_UNICODE);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    public function show($id): void
    {
        try {
            $id = (int)($id ?? 0);
            if ($id <= 0) {
                http_response_code(400);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['error' => 'Parámetro id inválido']);
                return;
            }

            $row = null;
            $row = $this->model->getWorldCupById($id);
            if ($row) {
                if (!isset($row['banner_b64']) && isset($row['banner']) && $row['banner'] !== null) {
                    $row['banner_b64'] = base64_encode($row['banner']);
                }
                // Nunca enviamos el binario en el JSON
                unset($row['banner']);
            }
            

            if (!$row) {
                http_response_code(404);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['error' => 'No encontrado']);
                return;
            }

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(
                ['ok' => true, 'data' => $row],
                JSON_UNESCAPED_UNICODE
            );

        } catch (Throwable $e) {
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

}   

/* ===== Dispatcher local ===== */
$controller = new WorldCupController();
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

// recorta base path (p.ej., /FootBook) de forma case-insensitive
$base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
if ($base && stripos($uriPath, $base) === 0) {
    $uriPath = substr($uriPath, strlen($base));
}
$path = '/' . trim($uriPath, '/'); 
$low  = strtolower($path);

if ($method === 'GET' && $low === '/api/worldcups')    { $controller->index();    exit; }
//Aqui falta el ahora llamar y mandar el id a show 

http_response_code(404);
echo json_encode(['error' => 'Ruta no encontrada']);
exit;
