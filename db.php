<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Ajusta estos datos a los de tu hosting
const DB_HOST = 'localhost';
const DB_NAME = 'cepeax8net_mallqui';
const DB_USER = 'cepeax8net_sendy12';
const DB_PASS = 'Hug65712##$$$##';
const DB_CHARSET = 'utf8mb4';

function db() : PDO {
  static $pdo = null;
  if ($pdo === null) {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
  }
  return $pdo;
}

function is_logged_in() : bool {
  return isset($_SESSION['user_id']);
}

function require_login() {
  if (!is_logged_in()) {
    header('Location: index.php');
    exit;
  }
}

function require_role(string $role) {
  require_login();
  if (($_SESSION['user_role'] ?? null) !== $role) {
    http_response_code(403);
    echo "Acceso denegado.";
    exit;
  }
}

function current_user_id() {
  return $_SESSION['user_id'] ?? null;
}

function cents_to_money(int $cents) : string {
  return number_format(($cents / 100), 2, '.', ',');
}

function flash($key) {
  if (!isset($_SESSION['_flash'])) return null;
  $v = $_SESSION['_flash'][$key] ?? null;
  unset($_SESSION['_flash'][$key]);
  return $v;
}

function set_flash($key, $val) {
  $_SESSION['_flash'][$key] = $val;
}
?>
