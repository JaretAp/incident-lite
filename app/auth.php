<?php

session_start();
function require_login() {
  if (empty($_SESSION['user'])) { header('Location: /public/login.php'); exit; }
}
function current_user() { return $_SESSION['user'] ?? null; }
function is_agent() { return (current_user()['role'] ?? '') === 'agent'; }