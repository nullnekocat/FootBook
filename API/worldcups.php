<?php
// API/worldcups.php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../core/Database.php';

// Helper function para respuestas consistentes
function jsonResponse($data, $statusCode = 200)
{
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Conexión a BD
try {
    $db = new Database();
} catch (Exception $e) {
    jsonResponse(['ok' => false, 'error' => 'Database connection failed: ' . $e->getMessage()], 500);
}

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Parse URL para obtener ID si existe
$pathParts = explode('/', trim(parse_url($uri, PHP_URL_PATH), '/'));
$id = null;

// Buscar ID en la URL (después de worldcups.php)
$foundApi = false;
foreach ($pathParts as $idx => $part) {
    if ($part === 'worldcups.php') {
        $foundApi = true;
        if (isset($pathParts[$idx + 1]) && is_numeric($pathParts[$idx + 1])) {
            $id = intval($pathParts[$idx + 1]);
        }
        break;
    }
}

// Si no se encontró en path, buscar en query string
if (!$id && isset($_GET['id'])) {
    $id = intval($_GET['id']);
}

switch ($method) {
    case 'GET':
        handleGet($db, $id);
        break;

    case 'POST':
        handlePost($db);
        break;

    case 'PUT':
        handlePut($db, $id);
        break;

    case 'DELETE':
        handleDelete($db, $id);
        break;

    default:
        jsonResponse(['ok' => false, 'error' => 'Method not allowed'], 405);
}

// ==================== HANDLERS ====================

function handleGet($db, $id)
{
    $type = $_GET['type'] ?? 'full';

    try {
        if ($id) {
            // Obtener un mundial específico
            $viewName = $type === 'light' ? 'v_lista_ligera_de_mundiales' : 'v_lista_de_mundiales';
            $rows = $db->callView($viewName, "WHERE status = 1 AND id = {$id} LIMIT 1");

            if (empty($rows)) {
                jsonResponse(['ok' => false, 'error' => 'World Cup not found'], 404);
            }

            jsonResponse(['ok' => true, 'data' => $rows[0]]);
        } else {
            // Obtener todos los mundiales
            $viewName = $type === 'light' ? 'v_lista_ligera_de_mundiales' : 'v_lista_de_mundiales';
            $rows = $db->callView($viewName, 'WHERE status = 1 ORDER BY year DESC');

            jsonResponse(['ok' => true, 'data' => $rows]);
        }
    } catch (Exception $e) {
        jsonResponse(['ok' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
    }
}

function handlePost($db)
{
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        jsonResponse(['ok' => false, 'error' => 'Invalid JSON'], 400);
    }

    // Validaciones básicas
    $required = ['name', 'country', 'year'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            jsonResponse(['ok' => false, 'error' => "Field '$field' is required"], 400);
        }
    }

    try {
        $bannerBinary = null;

        if (!empty($data['banner'])) {
            // Eliminar encabezado data URL si existe
            $cleanBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $data['banner']);
            $bannerBinary = base64_decode($cleanBase64);

            if ($bannerBinary === false) {
                jsonResponse(['ok' => false, 'error' => 'Invalid base64 banner'], 400);
            }
        }

        $params = [
            $data['name'],
            $data['country'],
            intval($data['year']),
            $data['description'] ?? '',
            $bannerBinary,
            $data['status'] ?? 1
        ];

        $types = [
            PDO::PARAM_STR,
            PDO::PARAM_STR,
            PDO::PARAM_INT,
            PDO::PARAM_STR,
            $bannerBinary === null ? PDO::PARAM_NULL : PDO::PARAM_LOB,
            PDO::PARAM_INT
        ];

        $stmt = $db->callSP('sp_create_worldcup', $params, $types);


        // Obtener el ID insertado (si el SP lo devuelve)
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $db->finish($stmt);

        // Si el SP devuelve el ID, úsalo; si no, intenta LAST_INSERT_ID
        $newId = $result['id'] ?? $result['worldcup_id'] ?? null;

        jsonResponse([
            'ok' => true,
            'message' => 'World Cup created successfully',
            'data' => ['id' => $newId]
        ], 201);
    } catch (Exception $e) {
        jsonResponse(['ok' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
    }
}

function handlePut($db, $id)
{
    if (!$id) {
        // Intentar obtener ID del body
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? null;
    }

    if (!$id) {
        jsonResponse(['ok' => false, 'error' => 'ID is required for update'], 400);
    }

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        jsonResponse(['ok' => false, 'error' => 'Invalid JSON'], 400);
    }

    try {
        // Verificar que existe
        $exists = $db->callView('v_lista_ligera_de_mundiales', "WHERE id = {$id} AND status = 1 LIMIT 1");

        if (empty($exists)) {
            jsonResponse(['ok' => false, 'error' => 'World Cup not found'], 404);
        }

        // Obtener valores actuales para usar como default
        $current = $exists[0];
        $bannerBinary = null;

        if (isset($data['banner'])) {
            if ($data['banner'] === null || $data['banner'] === '') {
                $bannerBinary = null; // El usuario quiere borrar el banner
            } else {
                $cleanBase64 = preg_replace('/^data:image\/\w+;base64,/', '', $data['banner']);
                $bannerBinary = base64_decode($cleanBase64);

                if ($bannerBinary === false) {
                    jsonResponse(['ok' => false, 'error' => 'Invalid base64 banner'], 400);
                }
            }
        } else {
            // No se envió banner: mantener el actual
            $bannerBinary = $current['banner'];
        }

        $params = [
            intval($id),
            $data['name'] ?? $current['name'],
            $data['country'] ?? $current['country'],
            isset($data['year']) ? intval($data['year']) : intval($current['year']),
            $data['description'] ?? $current['description'],
            $bannerBinary,
            $data['status'] ?? $current['status']
        ];

        $types = [
            PDO::PARAM_INT,
            PDO::PARAM_STR,
            PDO::PARAM_STR,
            PDO::PARAM_INT,
            PDO::PARAM_STR,
            $bannerBinary === null ? PDO::PARAM_NULL : PDO::PARAM_LOB,
            PDO::PARAM_INT
        ];

        // Llamar al SP de actualización
        $stmt = $db->callSP('sp_update_worldcup', $params, $types);
        $db->finish($stmt);

        jsonResponse([
            'ok' => true,
            'message' => 'World Cup updated successfully',
            'data' => ['id' => $id]
        ]);
    } catch (Exception $e) {
        jsonResponse(['ok' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
    }
}

function handleDelete($db, $id)
{
    if (!$id) {
        jsonResponse(['ok' => false, 'error' => 'ID is required for delete'], 400);
    }

    $mode = $_GET['mode'] ?? 'soft';

    try {
        // Verificar que existe
        $exists = $db->callView('v_lista_ligera_de_mundiales', "WHERE id = {$id} LIMIT 1");

        if (empty($exists)) {
            jsonResponse(['ok' => false, 'error' => 'World Cup not found'], 404);
        }

        // Llamar al SP correspondiente
        if ($mode === 'hard') {
            $stmt = $db->callSP('sp_hard_delete_worldcup', [$id], [PDO::PARAM_INT]);
        } else {
            $stmt = $db->callSP('sp_soft_delete_worldcup', [$id], [PDO::PARAM_INT]);
        }

        $db->finish($stmt);

        jsonResponse([
            'ok' => true,
            'message' => "World Cup deleted successfully ($mode mode)",
            'data' => ['id' => $id, 'mode' => $mode]
        ]);
    } catch (Exception $e) {
        jsonResponse(['ok' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
    }
}
