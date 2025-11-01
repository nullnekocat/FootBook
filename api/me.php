<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
ob_clean();

require_once __DIR__ . '/../core/App.php';
require_once __DIR__ . '/../core/auth.php'; // tu helper con current_user()

try {
    $sessionUser = current_user();
    if (!$sessionUser) {
        http_response_code(401);
        echo json_encode(['error' => 'No autenticado']);
        exit;
    }

    $app  = new App();
    $userController = $app->loadController('UserController'); // para reutilizar el modelo
    // o carga el modelo directo si lo prefieres:
    require_once __DIR__ . '/../models/UserModel.php';
    $model = new UserModel();

    $user = $model->getUserDataById((int)$sessionUser['id']);
    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'Usuario no encontrado']);
        exit;
    }

    // Por seguridad, nunca regreses el hash
    unset($user['password']);

    echo json_encode(['user' => $user], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
