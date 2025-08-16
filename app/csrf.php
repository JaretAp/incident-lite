<?php
function csrf_token() {
  if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
  return $_SESSION['csrf'];
}
function verify_csrf($t) {
  if (empty($t) || !hash_equals($_SESSION['csrf'] ?? '', $t)) { http_response_code(400); exit('Bad CSRF'); }
}
