<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

function current_user() {
  return $_SESSION['user'] ?? null;
}

function require_login() {
  if (!current_user()) {
    header('Location: /FootBook/router.php?page=login');
    exit;
  }
}

function is_admin() {
  return !empty($_SESSION['user']['admin']);
}
