<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("../db_connection.php");

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Call stored procedure to get all users (sp_get_all_users)
        try {
            $stmt = $db->prepare("CALL sp_get_all_users()");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Make sure to advance past any extra result sets
            while ($stmt->nextRowset()) { }
            echo json_encode($users);
        } catch (PDOException $e) {
            echo json_encode(["error" => "DB error: " . $e->getMessage()]);
        }
        break;

    case 'POST':
        // Create a new user via stored procedure sp_create_user
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->username, $data->email, $data->password)) {
            echo json_encode(["error" => "Missing required fields: username, email, password"]);
            exit;
        }

        // Map optional fields and provide defaults where sensible
        $fullName = isset($data->fullName) ? $data->fullName : null;
        $birthday = isset($data->birthday) ? $data->birthday : null;
        $gender = isset($data->gender) ? (int)$data->gender : null;
        $birth_country = isset($data->birth_country) ? $data->birth_country : null;
        $country = isset($data->country) ? $data->country : null;
        $avatar = isset($data->avatar) ? base64_decode($data->avatar) : null; // expect base64 for binary
        $admin = isset($data->admin) ? (int)$data->admin : 0;

        // Hash password
        $hashedPassword = password_hash($data->password, PASSWORD_BCRYPT);

        try {
            $stmt = $db->prepare("CALL sp_create_user(:admin, :username, :email, :password, :fullname, :birthday, :gender, :birth_country, :country, :avatar)");

            $stmt->bindParam(':admin', $admin, PDO::PARAM_INT);
            $stmt->bindParam(':username', $data->username);
            $stmt->bindParam(':email', $data->email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':fullname', $fullName);
            $stmt->bindParam(':birthday', $birthday);
            $stmt->bindParam(':gender', $gender, PDO::PARAM_INT);
            $stmt->bindParam(':birth_country', $birth_country);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':avatar', $avatar, PDO::PARAM_LOB);

            $result = $stmt->execute();
            // advance any extra resultsets
            while ($stmt->nextRowset()) { }

            if ($result) {
                echo json_encode(["message" => "Usuario creado correctamente."]);
            } else {
                echo json_encode(["error" => "Error al crear usuario."]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "DB error: " . $e->getMessage()]);
        }

        break;

    case 'DELETE':
        // Hard delete using stored procedure sp_hard_delete_user
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id) && !isset($data->user_id)) {
            echo json_encode(["error" => "Debe proporcionar un ID (id or user_id)."]);
            exit;
        }

        $id = isset($data->id) ? $data->id : $data->user_id;

        try {
            $stmt = $db->prepare("CALL sp_hard_delete_user(:id)");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            while ($stmt->nextRowset()) { }

            // Note: PDO doesn't provide affected rows for CALL reliably; check existence beforehand if needed.
            echo json_encode(["message" => "Solicitud de eliminación ejecutada para ID $id."]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "DB error: " . $e->getMessage()]);
        }

        break;

    default:
        echo json_encode(["error" => "Método no permitido."]);
        break;
}
?>