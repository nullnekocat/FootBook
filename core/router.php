<?php
namespace Core;

class Router {
    private array $routes = [];

    private function normalize(string $path): string {
        $path = parse_url($path, PHP_URL_PATH) ?? '/';
        $path = '/' . trim($path, '/');
        if ($path !== '/') $path = rtrim($path, '/');
        // match case-insensitive
        return strtolower($path);
    }

    public function add(string $path, string $controller): void {
        $this->routes[$this->normalize($path)] = ['controller' => $controller];
    }

    public function dispatch(string $requestUri): void {
        // path solicitado
        $requestPath = parse_url($requestUri, PHP_URL_PATH) ?? '/';

        // base path donde vive index.php (ej: /Footbook)
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
        if ($base && str_starts_with($requestPath, $base)) {
            $requestPath = substr($requestPath, strlen($base));
        }
        if ($requestPath === '' || $requestPath === false) $requestPath = '/';

        $norm = $this->normalize($requestPath);

        if (isset($this->routes[$norm])) {
            $controller = $this->routes[$norm]['controller'];
            $file = __DIR__ . '/../' . ltrim($controller, '/');
            if (is_file($file)) {
                require $file;
                return;
            }
            $this->abort(500, "Controller not found: {$file}");
        }

        $this->abort(404);
    }

    public function abort(int $code = 404, string $message = 'Page not found.'): void {
        http_response_code($code);
        echo "Error {$code}: {$message}";
        exit;
    }
}
