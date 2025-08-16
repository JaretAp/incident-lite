<?php
require __DIR__.'/../app/db.php';
require __DIR__.'/../app/auth.php';
require __DIR__.'/../app/csrf.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  verify_csrf($_POST['csrf'] ?? '');
  $stmt = $pdo->prepare('SELECT * FROM users WHERE email=?');
  $stmt->execute([trim($_POST['email'] ?? '')]);
  $u = $stmt->fetch();
  if ($u && password_verify($_POST['password'] ?? '', $u['password_hash'])) {
    $_SESSION['user'] = ['id'=>$u['id'], 'name'=>$u['name'], 'role'=>$u['role']];
    header('Location: /public/tickets.php'); exit;
  }
  $error = 'Invalid credentials';
}
?>
<form method="post">
  <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
  <input name="email" type="email" placeholder="Email">
  <input name="password" type="password" placeholder="Password">
  <button>Login</button>
  <?php if (!empty($error)) echo "<p>$error</p>"; ?>
</form>
