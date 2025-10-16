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
        // Obtener todos los usuarios
        $stmt = $db->prepare("SELECT user_id, fullName, username, email, created_at FROM FootBook.usuarios");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;

    case 'POST':
        // Crear un nuevo usuario
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->fullName, $data->username, $data->email, $data->password)) {
            echo json_encode(["error" => "Faltan campos obligatorios."]);
            exit;
        }

        // Hashear la contraseña antes de guardarla
        $hashedPassword = password_hash($data->password, PASSWORD_BCRYPT);

        $stmt = $db->prepare("
            INSERT INTO FootBook.usuarios (fullName, username, email, password)
            VALUES (:fullName, :username, :email, :password)
        ");
        $stmt->bindParam(":fullName", $data->fullName);
        $stmt->bindParam(":username", $data->username);
        $stmt->bindParam(":email", $data->email);
        $stmt->bindParam(":password", $hashedPassword);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Usuario creado correctamente."]);
        } else {
            echo json_encode(["error" => "Error al crear usuario."]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->user_id)) {
            echo json_encode(["error" => "Debe proporcionar un ID."]);
            exit;
        }

        $stmt = $db->prepare("DELETE FROM FootBook.usuarios WHERE user_id = :user_id");
        $stmt->bindParam(":user_id", $data->user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["message" => "Usuario eliminado correctamente."]);
        } else {
            echo json_encode(["error" => "Usuario no encontrado."]);
        }
        break;

    default:
        echo json_encode(["error" => "Método no permitido."]);
        break; 
}
?>