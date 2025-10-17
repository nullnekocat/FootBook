<?php
include_once('../db_connection.php');

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$database = new Database();
$db = $database->getConnection();

// Helper: detect image MIME
function detectImageMime($binary) {
    $info = @getimagesizefromstring($binary);
    return $info ? $info['mime'] : 'image/jpeg';
}

// Helper: encode banner blob properly
function encodeBanner($binary) {
    if (empty($binary)) return null;
    $mime = detectImageMime($binary);
    return "data:$mime;base64," . base64_encode($binary);
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGet($db);
        break;

    case 'POST':
        handlePost($db);
        break;

    case 'PUT':
        handlePut($db);
        break;

    case 'DELETE':
        handleDelete($db);
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}

$db = null;

// GET
function handleGet($db) {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $light = isset($_GET['light']) && $_GET['light'] == 1;

    try {
        if ($id > 0) {
            if ($light) {
                $stmt = $db->prepare("CALL sp_get_worldcup_by_id_light(:id)");
            } else {
                $stmt = $db->prepare("CALL sp_get_worldcup_by_id(:id)");
            }
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            if ($light) {
                $stmt = $db->prepare("CALL sp_get_all_worldcups_light()");
            } else {
                $stmt = $db->prepare("CALL sp_get_all_worldcups()");
            }
            $stmt->execute();
        }

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as &$row) {
            if (isset($row['banner'])) {
                $row['banner'] = encodeBanner($row['banner']);
            }
        }

        echo json_encode($result);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// POST (create)
function handlePost($db) {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode(["error" => "Invalid input"]);
        return;
    }

    $stmt = $db->prepare("CALL sp_create_worldcup(:name, :country, :year, :description, :banner, :status)");
    $stmt->bindParam(":name", $data['name']);
    $stmt->bindParam(":country", $data['country']);
    $stmt->bindParam(":year", $data['year']);
    $stmt->bindParam(":description", $data['description']);
    $stmt->bindParam(":banner", base64_decode($data['banner'] ?? ""), PDO::PARAM_LOB);
    $stmt->bindParam(":status", $data['status']);
    $stmt->execute();

    echo json_encode(["message" => "WorldCup created"]);
}

// PUT (update)
function handlePut($db) {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data || !isset($data['id'])) {
        echo json_encode(["error" => "Invalid input"]);
        return;
    }

    $stmt = $db->prepare("CALL sp_update_worldcup(:id, :name, :country, :year, :description, :banner, :status)");
    $stmt->bindParam(":id", $data['id']);
    $stmt->bindParam(":name", $data['name']);
    $stmt->bindParam(":country", $data['country']);
    $stmt->bindParam(":year", $data['year']);
    $stmt->bindParam(":description", $data['description']);
    $stmt->bindParam(":banner", base64_decode($data['banner'] ?? ""), PDO::PARAM_LOB);
    $stmt->bindParam(":status", $data['status']);
    $stmt->execute();

    echo json_encode(["message" => "WorldCup updated"]);
}

// DELETE (hard / soft)
function handleDelete($db) {
    parse_str(file_get_contents("php://input"), $data);
    $id = intval($data['id'] ?? 0);
    $soft = isset($data['soft']) && $data['soft'] == 1;

    if ($id <= 0) {
        echo json_encode(["error" => "Invalid ID"]);
        return;
    }

    if ($soft) {
        $stmt = $db->prepare("CALL sp_soft_delete_worldcup(:id)");
    } else {
        $stmt = $db->prepare("CALL sp_hard_delete_worldcup(:id)");
    }
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["message" => $soft ? "WorldCup soft deleted" : "WorldCup hard deleted"]);
}
?>
