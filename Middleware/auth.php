<?php
// /Middleware/auth.php
namespace Auth;

session_start();

/* ------------------ FUNCIONES DE LOGIN ------------------ */

function current_user() {
    return $_SESSION['user_id'] ?? null;
}

// Esta función valida el login del usuario y lo guarda en sesión
function login($user) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['is_admin'] = $user['is_admin']; 
}

// Esta función realiza el logout
function logout() {
    session_unset();
    session_destroy();
}


/* ------------------ FUNCIONES DE AUTORIZACIÓN ------------------ */

// Verifica si el usuario está autenticado
function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /FootBook');
        exit();
    }
}

// Verifica si el usuario es administrador
function checkAdmin() {
    if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
        header('Location: /FootBook/home');
        exit();
    }
}

// Verifica si el usuario está autenticado y no es admin
function checkUser() {
    checkAuth();
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === 1) {
        header('Location: /FootBook/admin');
        exit();
    }
}

// Verifica que el usuario esté autenticado y sea admin
function checkAuthAndAdmin() {
    checkAuth();  
    checkAdmin(); 
}

// Esta función protege el acceso de usuarios no autenticados
function require_login() {
    checkAuth();  
}