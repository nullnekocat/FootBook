<?php
// core/router.php

class Router
{
    private array $routes = [
        'GET'  => [],
        'POST' => [],
        // Las vistas se guardan como GET también, pero usando add() por legibilidad
    ];

    private string $basePath;

    public function __construct(?string $basePath = null)
    {
        // Detecta el subdirectorio (p.ej. /FootBook) automáticamente
        $this->basePath = $basePath !== null
            ? rtrim($basePath, '/')
            : rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');

        if ($this->basePath === '/') $this->basePath = '';
    }

    /* ===== Registro de rutas ===== */
    public function get(string $pattern, $handler): void
    {
        $this->map('GET', $pattern, $handler);
    }

    public function post(string $pattern, $handler): void
    {
        $this->map('POST', $pattern, $handler);
    }

    /**
     * add(): úsalo para VISTAS (archivos PHP bajo /views).
     * Internamente las registra como GET pero el handler es un "view".
     */
    public function add(string $pattern, callable|string $handler): void
    {
        $this->map('GET', $pattern, $handler);
    }

    private function map(string $method, string $pattern, $handler): void
    {
        // Convierte /api/worldcups/:id/banner -> regex con captura nombrada (?P<id>[^/]+)
        $regex = $this->patternToRegex($pattern);
        $this->routes[$method][] = [
            'pattern' => $pattern,
            'regex'   => $regex,
            'handler' => $handler
        ];
    }

    private function patternToRegex(string $pattern): string
    {
        // Asegura que el patrón incluya el basePath al inicio
        $full = $this->withBase($pattern);

        // Reemplaza :param por (?P<param>[^/]+)
        $full = preg_replace('#:([A-Za-z_][A-Za-z0-9_]*)#', '(?P<$1>[^/]+)', $full);

        return '#^' . rtrim($full, '/') . '/?$#';
    }

    private function withBase(string $path): string
    {
        if ($this->basePath && !str_starts_with($path, $this->basePath)) {
            return $this->basePath . (str_starts_with($path, '/') ? '' : '/') . $path;
        }
        return $path;
    }

    /* ===== Despacho ===== */
    public function dispatch(): void
    {
        $method   = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        // Solo la ruta sin querystring
        $uriPath  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        $list = $this->routes[$method] ?? [];

        foreach ($list as $r) {
            if (preg_match($r['regex'], $uriPath, $m)) {
                // Parámetros nombrados de la ruta
                $params = [];
                foreach ($m as $k => $v) {
                    if (!is_int($k)) $params[$k] = urldecode($v);
                }
                $this->invoke($r['handler'], $params);
                return;
            }
        }

        // 404
        http_response_code(404);
        $this->json(['ok' => false, 'error' => 'Route not found', 'path' => $uriPath]);
    }

    private function invoke(callable|string $handler, array $params = []): void
{
    // --- 1️⃣ Controladores tipo "Controller@method" (APIs) ---
    if (is_string($handler) && str_contains($handler, '@')) {
        [$ctrl, $method] = explode('@', $handler, 2);
        $ctrlFile = $this->resolveController($ctrl);

        if (!is_file($ctrlFile)) {
            http_response_code(500);
            echo "Controller file not found: $ctrlFile";
            return;
        }

        require_once $ctrlFile;

        if (!class_exists($ctrl)) {
            http_response_code(500);
            echo "Controller not found: $ctrl";
            return;
        }

        $instance = new $ctrl();
        if (!method_exists($instance, $method)) {
            http_response_code(500);
            echo "Method not found: $ctrl@$method";
            return;
        }

        // Pasa parámetros nombrados
        $ref = new ReflectionMethod($instance, $method);
        $args = [];
        foreach ($ref->getParameters() as $p) {
            $name = $p->getName();
            $args[] = $params[$name] ?? null;
        }

        $ref->invokeArgs($instance, $args);
        return;
    }

    // --- 2️⃣ Closures (rutas registradas con funciones anónimas) ---
    if (is_callable($handler)) {
        call_user_func_array($handler, $params);
        return;
    }

    // --- 3️⃣ Archivos de vista (rutas tipo add('/home', 'views/home.php')) ---
    if (is_string($handler) && is_file($handler)) {
        require $handler;
        return;
    }

    // --- ❌ Error por handler inválido ---
    http_response_code(500);
    echo 'Invalid route handler type';
}


    private function resolveController(string $ctrl): string
    {
        // controllers/WorldCupApi.php
        $root = realpath(__DIR__ . '/..');
        return $root . '/controllers/' . $ctrl . '.php';
    }

    private function resolveView(string $view): string
    {
        // Permite pasar "wiki.php" o "views/wiki.php"
        $root = realpath(__DIR__ . '/..');
        if (str_starts_with($view, 'views/')) {
            return $root . '/' . $view;
        }
        return $root . '/views/' . $view;
    }

    /* ===== Helpers ===== */
    public function json($data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
