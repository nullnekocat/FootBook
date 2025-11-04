<?php
// router.php
use Core\Router;

$router = new Router();

// Rutas a Vistas
$router->add('/', 'controllers/landing.php'); //Esto será el landing page
$router->add('/home', 'controllers/home.php'); //Esto será la pagina principal (despues de login)
$router->add('/login', 'controllers/login.php'); 
$router->add('/signup', 'controllers/signup.php'); 
$router->add('/profile', 'controllers/profile.php'); 
$router->add('/wiki', 'controllers/wiki.php');
$router->add('/admin', 'controllers/admin.php');

//Rutas a Controladores
$router->add('/api/categories', 'controllers/CategoryController.php');

// Ejecuta
$router->dispatch($_SERVER['REQUEST_URI']);