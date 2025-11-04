<?php

function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

function base_path(): string {
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
    return $base ?: '';
}

// === Base URL canónica (respetando mayúsculas) ===
const APP_BASE = '/FootBook';  // <- aquí fuerzas la B mayúscula

function url(string $path = ''): string {
    $path = '/' . ltrim($path, '/');
    return APP_BASE . ($path === '//' ? '/' : $path);
}

function redirect(string $path = '/', int $code = 302): void {
    header('Location: ' . url($path), true, $code);
    exit;
}
