<?php
require __DIR__.'/../app/db.php';
require __DIR__.'/../app/auth.php';
require __DIR__.'/../app/csrf.php';

if ($_SERVER['REQUEST_METHOD']==='POST') {
  verify_csrf($_POST['csrf'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $name  = trim($_POST['name'] ?? '');
  $pass  = $_POST['password'] ?? '';
  if (!$email || !$name || strlen($pass) < 6) die('Invalid input');
  $stmt = $pdo->prepare('INSERT INTO users (email,password_hash,name) VALUES (?,?,?)');
  $stmt->execute([$email, password_hash($pass, PASSWORD_DEFAULT), $name]);
  header('Location: /public/login.php'); exit;
}
?>
<form method="post">
  <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
  <input name="name" placeholder="Name">
  <input name="email" type="email" placeholder="Email">
  <input name="password" type="password" placeholder="Password (min 6)">
  <button>Register</button>
</form>
