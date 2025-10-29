<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
ob_clean();

require_once __DIR__ . '/../core/App.php';

try {
    $app = new App();
    $auth = $app->loadController('AuthController');
    $auth->handleLogin();
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
exit;
