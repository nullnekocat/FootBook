<?php
namespace Auth;

if (session_status() !== \PHP_SESSION_ACTIVE) {
    session_start();
}

/** Guarda al usuario en sesión */
function login(int $id, string $username, int $admin = 0): void {
    $_SESSION['user'] = [
        'id'       => $id,
        'username' => $username,
        'admin'    => (int)$admin,
    ];
}

/** Devuelve el usuario en sesión o null */
function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

/** ¿Es admin? */
function is_admin(): bool {
    return !empty($_SESSION['user']['admin']);
}

/** Requiere login (redirecciona a /FootBook/login) */
function require_login(): void {
    if (!current_user()) {
        header('Location: /FootBook/login');
        exit;
    }
}

/** Requiere admin (403 si no lo es) */
function require_admin(): void {
    if (!is_admin()) {
        http_response_code(403);
        echo 'Forbidden';
        exit;
    }
}

/** Cerrar sesión por completo */
function logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
