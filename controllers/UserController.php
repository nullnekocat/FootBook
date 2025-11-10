<?php
declare(strict_types=1);

use Models\UserModel;

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../Middleware/auth.php';

use function Auth\current_user;

class UserController {
    private UserModel $model;
    
    public function __construct() {
        $this->model = new UserModel(); // usa tus SP vía Database
        header('Content-Type: application/json; charset=UTF-8');
    }
/* ----------------- helpers ----------------- */
    private function json($data, int $code = 200): void
    {
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    private function json_in(): array
    {
        // Acepta JSON y/x-www-form-urlencoded por compat
        $in = $_POST;
        if (empty($in)) {
            $raw = file_get_contents('php://input');
            if ($raw !== false && $raw !== '') {
                $j = json_decode($raw, true);
                if (is_array($j)) $in = $j;
            }
        }
        return $in ?: [];
    }
    private function json_out(int $code, array $payload): void
    {
        http_response_code($code);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }
/* ------------------ metodos ---------------------- */
    public function index(): void { //index = list
        try{
            $rows = $this->model->getListOfUsers();
            $this->json(['ok' => true, 'data' => $rows], 200);
        } catch (Throwable $e){
            $this->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }
    public function register(): void
    {
        try {
            $in = $this->json_in();

            // Requeridos según tu tabla/SP
            $required = [
                'username','email','password','fullname',
                'birthday','gender','birth_country','country'
            ];
            foreach ($required as $r) {
                if (!isset($in[$r]) || trim((string)$in[$r]) === '') {
                    $this->json_out(422, ['ok'=>false,'error'=>"Campo requerido: $r"]);
                    return;
                }
            }

            // Limpia avatar si viene como data URL
            $avatarB64 = $in['avatar'] ?? null;
            if (is_string($avatarB64) && str_starts_with($avatarB64, 'data:')) {
                $pos = strpos($avatarB64, 'base64,');
                if ($pos !== false) $avatarB64 = substr($avatarB64, $pos + 7);
            }

            // Normaliza/asegura tipos
            $data = [
                'admin'         => (int)($in['admin'] ?? 0),
                'username'      => trim((string)$in['username']),
                'email'         => trim((string)$in['email']),
                'password'      => password_hash((string)$in['password'], PASSWORD_BCRYPT), // 🔐
                'fullname'      => trim((string)$in['fullname']),
                'birthday'      => (string)$in['birthday'],      // YYYY-MM-DD
                'gender'        => (int)$in['gender'],           // 1/2/3 (ajusta a tu catálogo)
                'birth_country' => trim((string)$in['birth_country']),
                'country'       => trim((string)$in['country']),
                'avatar'        => ($avatarB64 && $avatarB64 !== '') ? base64_decode($avatarB64) : null, // LONGBLOB
            ];

            // Validaciones mínimas
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->json_out(422, ['ok'=>false,'error'=>'Email inválido']);
                return;
            }
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['birthday'])) {
                $this->json_out(422, ['ok'=>false,'error'=>'birthday debe ser YYYY-MM-DD']);
                return;
            }

            // Llama a tu SP vía el modelo
            $this->model->createUser($data);

            $this->json_out(201, [
                'ok'      => true,
                'message' => 'Usuario creado correctamente',
                'redirect'=> '/FootBook/login'
            ]);
        } catch (\Throwable $e) {
            $code = (int)($e->getCode() ?: 500);
            $this->json_out($code, ['ok'=>false,'error'=>$e->getMessage()]);
        }
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
            \Auth\login([
                'user_id'       => $user['id'],
                'username' => $user['username'] ?? null,
                'is_admin'    => (int)($user['admin'] ?? 0),
            ]);
                       
            // Éxito
            $this->json_out(200, [
                'ok'   => true,
                'user' => [
                    'user_id'       => $user['id'] ?? $user['user_id'] ?? null,
                    'identity' => $user['username'] ?? null,
                    'admin'    => (int)($user['admin'] ?? 0),
                ],
                'redirect' => '/FootBook/home'
            ]);
            
        }catch (\Throwable $e) {
            $this->json_out(500, ['error' => 'Server error', 'detail' => $e->getMessage()]);
        }
    }   
    public function me(): void
    {
        try {
            // Autenticación por sesión
            $userId = current_user();
            if (!$userId) {
                $this->json_out(401, ['ok' => false, 'error' => 'No autenticado']);
                return;
            }

         
            $rows = $this->model->getUserDataById((int)$userId);
            $row = is_array($rows) && array_is_list($rows) ? ($rows[0] ?? null) : $rows;

            if (!$row) {
                $this->json_out(404, ['ok' => false, 'error' => 'Usuario no encontrado']);
                return;
            }

            $this->json_out(200, ['ok' => true, 'data' => $row]);

        } catch (\Throwable $e) {
            $this->json_out(500, ['ok' => false, 'error' => 'Server error', 'detail' => $e->getMessage()]);
        }
    }
    public function update(): void
    {
        try {
            $in = $this->json_in();

            // El user_id viene de la sesión
            $userId = current_user();
            if (!$userId) {
                $this->json_out(401, ['ok'=>false,'error'=>'No autenticado']);
                return;
            }

            // Campos opcionales para actualizar
            $data = ['id' => (int)$userId];

            // Solo actualiza los campos que vengan en el request
            if (isset($in['fullname']) && trim($in['fullname']) !== '') {
                $data['fullname'] = trim($in['fullname']);
            }
            if (isset($in['username']) && trim($in['username']) !== '') {
                $data['username'] = trim($in['username']);
            }
            if (isset($in['email']) && trim($in['email']) !== '') {
                $email = trim($in['email']);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->json_out(422, ['ok'=>false,'error'=>'Email inválido']);
                    return;
                }
                $data['email'] = $email;
            }
            if (isset($in['birthday']) && trim($in['birthday']) !== '') {
                $birthday = trim($in['birthday']);
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday)) {
                    $this->json_out(422, ['ok'=>false,'error'=>'birthday debe ser YYYY-MM-DD']);
                    return;
                }
                $data['birthday'] = $birthday;
            }
            if (isset($in['gender']) && $in['gender'] !== '') {
                $data['gender'] = (int)$in['gender'];
            }
            if (isset($in['birth_country']) && trim($in['birth_country']) !== '') {
                $data['birth_country'] = trim($in['birth_country']);
            }
            if (isset($in['country']) && trim($in['country']) !== '') {
                $data['country'] = trim($in['country']);
            }

            // Avatar (base64)
            if (isset($in['avatar']) && $in['avatar'] !== '') {
                $avatarB64 = $in['avatar'];
                if (is_string($avatarB64) && str_starts_with($avatarB64, 'data:')) {
                    $pos = strpos($avatarB64, 'base64,');
                    if ($pos !== false) $avatarB64 = substr($avatarB64, $pos + 7);
                }
                $data['avatar'] = base64_decode($avatarB64);
            }

            // Password (opcional)
            if (isset($in['password']) && trim($in['password']) !== '') {
                $data['password'] = password_hash(trim($in['password']), PASSWORD_BCRYPT);
            }

            // Llamar al método del modelo
            $this->model->updateUser($data);

            $this->json_out(200, [
                'ok'      => true,
                'message' => 'Perfil actualizado correctamente'
            ]);

        } catch (\Throwable $e) {
            $code = (int)($e->getCode() ?: 500);
            $this->json_out($code, ['ok'=>false,'error'=>$e->getMessage()]);
        }
    }

}