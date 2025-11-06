<?php
// routes.php
// Incluye el router y lo inicializa

use function Auth\require_login;
use function Auth\checkAuthAndAdmin;

require_once __DIR__ . '/core/router.php';
require_once __DIR__ . '/Middleware/auth.php';

$router = new Router(); // detecta /FootBook automáticamente

/* ===== VISTAS (add) ===== */

$router->add('/FootBook',             'views/landing.php');
$router->add('/FootBook/home', function(){        
    require_login(); //Te redirige si no estás logueado
    require 'views/index.php';
}); 
$router->add('/FootBook/wiki',        'views/wiki.php'); 
$router->add('/FootBook/login',       'views/login.php');
$router->add('/FootBook/profile', function(){        
    require_login(); //Te redirige si no estás logueado
    require 'views/profile.php';
});
$router->add('/FootBook/signup',      'views/signup.php');
/**/
$router->add('/FootBook/admin', function(){        
    checkAuthAndAdmin(); //Te redirige si no estás logueado
    require 'views/admin.php';
});
//$router->add('/FootBook/admin',       'views/admin.php');
$router->add('/FootBook/results',       'views/results.php');
/* ===== Controladores =====  */

    // ===== CATEGORIES ===== //
$router->get('/FootBook/api/categories',                'CategoryController@list'); //Lista 
$router->post('/FootBook/api/categories/:name',                'CategoryController@create'); //Crear

    // ===== USERS ===== //
$router->get('/FootBook/api/users',                'UserController@index'); 
$router->post('/FootBook/api/users/register',                'UserController@register');
$router->post('/FootBook/api/users/login',                'UserController@login');
$router->get('/FootBook/api/users/me',                'UserController@me');


/* ===== API WorldCups ===== */
$router->get('/FootBook/api/worldcups',                 'WorldCupApi@index');
$router->get('/FootBook/api/worldcups/:id',             'WorldCupApi@show');





/* ===== Despacho ===== */
$router->dispatch();
