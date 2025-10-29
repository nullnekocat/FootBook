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
        $status = 200;
        $resp   = null;

        try {
            switch ($method) {
                case 'POST':
                    $data = json_decode(file_get_contents('php://input'), true) ?: [];

                    // campos requeridos que tu front envía
                    $req = ['username','email','password','fullName','birthday','gender','birth_country','country'];
                    foreach ($req as $f) {
                        if (!isset($data[$f]) || $data[$f] === '') {
                            throw new RuntimeException("Falta el campo: $f", 400);
                        }
                    }

                    $avatarBinary = !empty($data['avatar']) ? base64_decode($data['avatar']) : null;

                    $newUser = [
                        'admin'         => (int)($data['admin'] ?? 0),
                        'username'      => $data['username'],
                        'email'         => $data['email'],
                        'password'      => password_hash($data['password'], PASSWORD_BCRYPT),
                        'fullname'      => $data['fullName'],
                        'birthday'      => $data['birthday'],
                        'gender'        => (int)$data['gender'],
                        'birth_country' => $data['birth_country'],
                        'country'       => $data['country'],
                        'avatar'        => $avatarBinary
                    ];

                    $ok = $this->userModel->createUser($newUser);
                    $resp = ['message' => 'Usuario creado exitosamente'];
                    break;

                case 'GET':
                    $resp = ['ok' => true]; // ajusta según necesites
                    break;

                default:
                    throw new RuntimeException('Método no permitido', 405);
            }

        } catch (RuntimeException $e) {
            $status = $e->getCode() ?: 400;
            $resp   = ['error' => $e->getMessage()];

        } catch (Throwable $e) {
            $status = 500;
            $resp   = ['error' => 'Error interno: ' . $e->getMessage()];
        }

        http_response_code($status);
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
