<?php
// routes.php
// Incluye el router y lo inicializa
require_once __DIR__ . '/core/router.php';
$router = new Router(); // detecta /FootBook automáticamente

/* ===== VISTAS (add) =====
   Usa add() para views. El handler es un archivo PHP dentro de /views. */

$router->add('/FootBook',             'landing.php');
$router->add('/FootBook/home',        'index.php');
$router->add('/FootBook/wiki',        'wiki.php');
$router->add('/FootBook/login',       'login.php');
$router->add('/FootBook/profile',     'profile.php');
$router->add('/FootBook/signup',      'signup.php');
$router->add('/FootBook/admin',      'admin.php');

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
