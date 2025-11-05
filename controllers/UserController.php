<?php
declare(strict_types=1);

use Models\UserModel;

require_once __DIR__ . '/../models/UserModel.php';

header('Content-Type: application/json; charset=UTF-8');

class UserController {
    private UserModel $model;

    public function __construct() {
        $this->model = new UserModel(); // usa tus SP vía Database
    }

    public function login(): void {
        try {
            $in = $this->json_in();
            $identity = trim((string)($in['identity'] ?? ''));
            $password = (string)($in['password'] ?? '');

            if ($identity === '' || $password === '') {
                $this->json_out(422, ['error' => 'Usuario/email y contraseña requeridos']);
                return;
            }

            // Tu Model llama al SP sp_get_user_for_login
            $user = $this->model->getUserForLogin($identity);
            if (!$user || !password_verify($password, $user['password'])) {
                $this->json_out(401, ['error' => 'Usuario o contraseña incorrectos']);
                return;
            }

            // Éxito
            $this->json_out(200, [
                'ok'   => true,
                'user' => [
                    'id'       => $user['id'] ?? $user['user_id'] ?? null,
                    'identity' => $user['username'] ?? null,
                    'admin'    => (int)($user['admin'] ?? 0),
                ],
                'redirect' => '/FootBook/home'
            ]);
        } catch (\Throwable $e) {
            $this->json_out(500, ['error' => 'Server error', 'detail' => $e->getMessage()]);
        }
    }

    public function register(): void {
        try {
            $in = $this->json_in();

            // Campos requeridos según tu tabla y SP
            $required = ['username','email','password','fullname','birthday','gender','birth_country','country'];
            foreach ($required as $r) {
                if (!isset($in[$r]) || $in[$r] === '') {
                    $this->json_out(422, ['error' => "Campo requerido: $r"]);
                    return;
                }
            }

            // Normaliza/asegura tipos
            $data = [
                'admin'         => (int)($in['admin'] ?? 0),
                'username'      => trim((string)$in['username']),
                'email'         => trim((string)$in['email']),
                'password'      => password_hash((string)$in['password'], PASSWORD_BCRYPT), // 🔐
                'fullname'      => trim((string)$in['fullname']),
                'birthday'      => (string)$in['birthday'],      // YYYY-MM-DD
                'gender'        => (int)$in['gender'],           // 1/2/3
                'birth_country' => trim((string)$in['birth_country']),
                'country'       => trim((string)$in['country']),
                // Si viene base64 desde el front, lo pasamos a binario para el LONGBLOB
                'avatar'        => isset($in['avatar']) && $in['avatar'] !== '' ? base64_decode($in['avatar']) : null,
            ];

            // Llama a tu SP vía el Model
            $this->model->createUser($data);

            $this->json_out(201, [
                'ok'      => true,
                'message' => 'Usuario creado correctamente',
                'redirect'=> '/FootBook/login'
            ]);
        } catch (\Throwable $e) {
            $code = $e->getCode() ?: 500;
            $this->json_out($code, ['error' => $e->getMessage()]);
        }
    }
    /* ----------------- helpers (mismo patrón) ----------------- */
    private function json_in(): array {
        $raw = file_get_contents('php://input') ?: '';
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private function json_out(int $code, array $payload = []): void {
        http_response_code($code);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

/* ===== Dispatcher local, igual estilo que CategoryController ===== */
$controller = new UserController();
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

// recorta base path (p.ej., /FootBook) de forma case-insensitive
$base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
if ($base && stripos($uriPath, $base) === 0) {
    $uriPath = substr($uriPath, strlen($base));
}
$path = '/' . trim($uriPath, '/');       // '/api/login'
$low  = strtolower($path);

if ($method === 'POST' && $low === '/api/users/login')    { $controller->login();    exit; }
if ($method === 'POST' && $low === '/api/users/register')    { $controller->register();    exit; }

http_response_code(404);
echo json_encode(['error' => 'Ruta no encontrada']);
exit;
