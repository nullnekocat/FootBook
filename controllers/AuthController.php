<?php
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
    public function handleLogin() {
        header('Content-Type: application/json; charset=utf-8');
        ini_set('display_errors', 0);
        ob_clean();

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                http_response_code(405);
                echo json_encode(['error' => 'Método no permitido']);
                exit;
            }

            $data = json_decode(file_get_contents('php://input'), true) ?: [];

            $identity = trim($data['identity'] ?? '');
            $password = (string)($data['password'] ?? '');

            if ($identity === '' || $password === '') {
                http_response_code(400);
                echo json_encode(['error' => 'Faltan credenciales']);
                exit;
            }

            $user = $this->userModel->getUserForLogin($identity);
            if (!$user) {
                http_response_code(401);
                echo json_encode(['error' => 'Usuario o contraseña incorrectos']);
                exit;
            }

            if (!password_verify($password, $user['password'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Usuario o contraseña incorrectos']);
                exit;
            }

            // Inicia sesión
            if (session_status() !== PHP_SESSION_ACTIVE) session_start();
            $_SESSION['user'] = [
                'id'       => (int)$user['id'],
                'username' => $user['username'],
                'email'    => $user['email'],
                'admin'    => (int)$user['admin']
            ];

            echo json_encode([
                'message'  => 'Login correcto',
                'user'     => $_SESSION['user'],
                'redirect' => '/FootBook/router.php?page=home'
            ]);
            exit;

        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Error interno: ' . $e->getMessage()]);
            exit;
        }
    }


    public function handleLogout() {
        header('Content-Type: application/json; charset=utf-8');
        ini_set('display_errors', 0);
        ob_clean();

        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION = [];
        session_destroy();
        echo json_encode(['message' => 'Sesión cerrada']);
        exit;
    }
}
