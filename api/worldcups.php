<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../db_connection.php');

$db = new Database();
$conn = $db->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // /worldcups.php?id=1&type=light
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        $type = isset($_GET['type']) ? $_GET['type'] : 'full';

        if ($id) {
            $stmt = $conn->prepare(
                $type === 'light'
                    ? "CALL sp_get_worldcup_by_id_light(:id)"
                    : "CALL sp_get_worldcup_by_id(:id)"
            );
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        } else {
            $stmt = $conn->prepare(
                $type === 'light'
                    ? "CALL sp_get_all_worldcups_light()"
                    : "CALL sp_get_all_worldcups()"
            );
        }

        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        // expects JSON body
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            echo json_encode(["error" => "Invalid JSON"]);
            exit;
        }

        $stmt = $conn->prepare("CALL sp_create_worldcup(:name,:country,:year,:description,:banner,:status)");
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':country', $data['country']);
        $stmt->bindParam(':year', $data['year']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':banner', $data['banner'], PDO::PARAM_LOB);
        $stmt->bindParam(':status', $data['status']);
        $stmt->execute();

        echo json_encode(["message" => "WorldCup created"]);
        break;

    case 'PUT':
        // expects JSON body
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data || !isset($data['id'])) {
            echo json_encode(["error" => "Missing id or invalid JSON"]);
            exit;
        }

        $stmt = $conn->prepare("CALL sp_update_worldcup(:id,:name,:country,:year,:description,:banner,:status)");
        $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':country', $data['country']);
        $stmt->bindParam(':year', $data['year']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':banner', $data['banner'], PDO::PARAM_LOB);
        $stmt->bindParam(':status', $data['status']);
        $stmt->execute();

        echo json_encode(["message" => "WorldCup updated"]);
        break;

    case 'DELETE':
        // /worldcups.php?id=1&mode=soft
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        $mode = isset($_GET['mode']) ? $_GET['mode'] : 'soft';
        if (!$id) {
            echo json_encode(["error" => "Missing id"]);
            exit;
        }

        if ($mode === 'hard') {
            $stmt = $conn->prepare("CALL sp_hard_delete_worldcup(:id)");
        } else {
            $stmt = $conn->prepare("CALL sp_soft_delete_worldcup(:id)");
        }
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(["message" => "WorldCup deleted ($mode)"]);
        break;

    default:
        echo json_encode(["error" => "Method not allowed"]);
}
?>
