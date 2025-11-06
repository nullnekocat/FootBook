<?php
declare(strict_types=1);

use Models\UserModel;

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../Middleware/auth.php';

header('Content-Type: application/json; charset=UTF-8');

class UserController {
    private UserModel $model;
    
    public function __construct() {
        $this->model = new UserModel(); // usa tus SP vía Database
    }

    public function me(): void {
        // requiere estar logueado
        $sessUser = \Auth\current_user();
        if (!$sessUser) {
            http_response_code(401);
            echo json_encode(['error' => 'No autenticado']);
            return;
        }

        $id = (int)$sessUser['id'];
        $row = $this->model->getUserDataById($id);

        if (!$row) {
            http_response_code(404);
            echo json_encode(['error' => 'Usuario no encontrado']);
            return;
        }

        $avatarExists = isset($row['avatar']) && $row['avatar'] !== null && $row['avatar'] !== '';
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
        $avatarUrl = ($avatarExists ? ($base . '/api/users/me/avatar?ts=' . time()) : null);

        $payload = [
            'user' => [
                'id'            => $id,
                'username'      => $row['username']      ?? $sessUser['username'] ?? null,
                'admin'         => (int)($row['admin']    ?? $sessUser['admin']    ?? 0),
                'fullname'      => $row['fullname']      ?? null,
                'email'         => $row['email']         ?? null,
                'birthday'      => $row['birthday']      ?? null,
                'gender'        => isset($row['gender']) ? (int)$row['gender'] : null,
                'birth_country' => $row['birth_country'] ?? null,
                'country'       => $row['country']       ?? null,
                'avatar_exists' => (bool)$avatarExists,
                'avatar_url'    => $avatarUrl,
            ],
        ];

        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    public function avatar(): void {
        $sessUser = \Auth\current_user();
        if (!$sessUser) { http_response_code(401); echo 'No autenticado'; return; }

        $id  = (int)$sessUser['id'];
        $row = $this->model->getUserDataById($id);
        $bin = $row['avatar'] ?? null;

        if (!$bin) { http_response_code(404); echo 'Sin avatar'; return; }

        // Detecta MIME de forma segura
        $mime = 'image/jpeg';
        if (function_exists('getimagesizefromstring')) {
            $info = @getimagesizefromstring($bin);
            if (!empty($info['mime'])) $mime = $info['mime'];
        } elseif (class_exists(\finfo::class)) {
            $f = new \finfo(FILEINFO_MIME_TYPE);
            $det = $f->buffer($bin);
            if ($det) $mime = $det;
        }

        // Limpia cualquier output/headers previos
        while (ob_get_level()) { ob_end_clean(); }
        header_remove('Content-Type');                  // elimina 'application/json'
        header('Content-Type: ' . $mime);
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');

        echo $bin;
        exit;
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
            // Guarda en sesión
            \Auth\login(
                (int)($user['id'] ?? $user['user_id']),
                (string)($user['username'] ?? ''),
                (int)($user['admin'] ?? 0)
            );
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

    public function getUsersList(): void {
        try{
            $rows = $this->model->getListOfUsers();
            $this->json(200, ['data' => $rows]);
        } catch (Throwable $e){
            $this->json(500, ['error' => 'Server error', 'detail' => $e->getMessage()]);
        }
    }

    /* ----------------- helpers ----------------- */
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
    private function json(int $status, array $data): void {
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }    
}

/* ===== Dispatcher local ===== */
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
if ($method === 'GET' && $low === '/api/users/list')    { $controller->getUsersList();    exit; }
if ($method === 'GET' && $low === '/api/users/me')    { $controller->me();    exit; }
if ($method === 'GET' && $low === '/api/users/me/avatar')    { $controller->avatar();    exit; }


http_response_code(404);
echo json_encode(['error' => 'Ruta no encontrada']);
exit;
