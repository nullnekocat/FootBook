<?php
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function handleRequest() {
        header('Content-Type: application/json; charset=utf-8');
        ini_set('display_errors', 0);
        ob_clean();

        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);

                if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
                    echo json_encode(["error" => "Campos obligatorios faltantes"]);
                    return;
                }

                // Decodificar imagen si existe
                $avatarBinary = !empty($data['avatar'])
                    ? base64_decode($data['avatar'])
                    : null;

                // Construir array compatible con el modelo
                $newUser = [
                    'admin' => $data['admin'] ?? 0,
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                    'fullname' => $data['fullName'],
                    'birthday' => $data['birthday'],
                    'gender' => $data['gender'],
                    'birth_country' => $data['birth_country'],
                    'country' => $data['country'],
                    'avatar' => $avatarBinary
                ];

                $ok = $this->userModel->createUser($newUser);
                echo json_encode(
                    $ok ? ["message" => "Usuario creado exitosamente"]
                        : ["error" => "No se pudo crear el usuario"]
                );
                return;

            case 'GET':
                echo json_encode($this->userModel->getAllUsers());
                return;

            default:
                http_response_code(405);
                echo json_encode(["error" => "Método no permitido"]);
        }
    }
}
