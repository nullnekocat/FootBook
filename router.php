<?php
// router.php

$views_dir = __DIR__ . '/views/';

$routes = [
    'home'      => 'index.php',
    'login'     => 'login.php',
    'signup'    => 'signup.php',
    'profile'   => 'profile.php',
    'results'   => 'results.php',
    'admin'     => 'admin.php',
    'wiki'      => 'wiki.php'
];

// Obtener la ruta desde el parámetro GET (?page=)
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Verificar si existe la ruta y el archivo
if (array_key_exists($page, $routes) && file_exists($views_dir . $routes[$page])) {
    include $views_dir . $routes[$page];
} else {
    http_response_code(404);
    echo "<h1>404 - Página no encontrada</h1>";
    echo "<a href='index.php'>Volver al inicio</a>";
}
?>
