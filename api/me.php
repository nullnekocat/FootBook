<?php
// Responde SIEMPRE JSON (aunque haya error)
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);

// Captura cualquier salida de includes (evita body vacío o “1”)
ob_start();

require_once __DIR__ . '/../core/auth.php';
require_once __DIR__ . '/../models/UserModel.php';

try {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();

    // Obtén al usuario de la sesión (ajusta si usas current_user())
    $sessionUser = $_SESSION['user'] ?? null;

    if (!$sessionUser) {
        ob_end_clean();
        http_response_code(401);
        echo json_encode(['error' => 'No autenticado']);
        exit;
    }

    $model = new UserModel();

    // Usa el método que LLAMA a tu SP sp_get_user_data
    $user = $model->getUserDataById((int)$sessionUser['id']);

    if (!$user) {
        ob_end_clean();
        http_response_code(404);
        echo json_encode(['error' => 'Usuario no encontrado']);
        exit;
    }

    unset($user['password']);

    // Tira cualquier eco/basura previa y responde JSON limpio
    ob_end_clean();
    echo json_encode(['user' => $user], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Throwable $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['error' => 'Error interno: '.$e->getMessage()]);
    exit;
}
