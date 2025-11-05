<?php
namespace Core;

class Router {
  private array $routes = [];

  private function normalize(string $path): string {
    $path = parse_url($path, PHP_URL_PATH) ?? '/';
    $path = '/' . trim($path, '/');
    if ($path !== '/') $path = rtrim($path, '/');
    return strtolower($path);
  }

  public function add(string $path, string $controller): void {
    $this->routes[$this->normalize($path)] = ['controller' => $controller];
  }

  public function dispatch(string $requestUri): void {
    $reqPath = parse_url($requestUri, PHP_URL_PATH) ?? '/';

    // recorta base path (p.ej. /FootBook) case-insensitive
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/\\');
    if ($base && stripos($reqPath, $base) === 0) {
      $reqPath = substr($reqPath, strlen($base));
    }
    if ($reqPath === '' || $reqPath === false) $reqPath = '/';

    $norm = $this->normalize($reqPath);

    if (isset($this->routes[$norm])) {
      $controller = __DIR__ . '/../' . ltrim($this->routes[$norm]['controller'], '/');
      if (is_file($controller)) { require $controller; return; }
      $this->abort(500, "Controller not found: {$controller}");
    }

    $this->abort(404);
  }

  public function abort(int $code=404, string $msg='Page not found.'): void {
    http_response_code($code);
    echo "Error {$code}: {$msg}";
    exit;
  }
}
