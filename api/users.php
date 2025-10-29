<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

ob_clean(); // limpia cualquier salida previa

require_once __DIR__ . '/../core/App.php';

try {
    $app = new App();
    $controller = $app->loadController('UserController');
    $controller->handleRequest();
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
exit;

