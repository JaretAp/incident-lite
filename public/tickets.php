<?php
require __DIR__.'/../app/db.php';
require __DIR__.'/../app/auth.php';
require_login();
if ($_SERVER['REQUEST_METHOD']==='POST') {
  require __DIR__.'/../app/csrf.php'; verify_csrf($_POST['csrf'] ?? '');
  $stmt = $pdo->prepare('INSERT INTO tickets (user_id,title,category,priority,description)
                         VALUES (?,?,?,?,?)');
  $stmt->execute([current_user()['id'], $_POST['title'], $_POST['category'], $_POST['priority'], $_POST['description']]);
  header('Location: tickets.php'); exit;
}
$onlyMine = isset($_GET['mine']);
$sql = 'SELECT t.*, u.name AS requester FROM tickets t JOIN users u ON u.id=t.user_id';
$sql .= $onlyMine ? ' WHERE t.user_id=? ORDER BY t.updated_at DESC' : ' ORDER BY t.updated_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($onlyMine ? [current_user()['id']] : []);
$tickets = $stmt->fetchAll();
?>
<h1>Tickets</h1>
<a href="?">All</a> | <a href="?mine=1">My Tickets</a>
<ul>
<?php foreach($tickets as $t): ?>
  <li><a href="ticket.php?id=<?=$t['id']?>"><?=htmlspecialchars($t['title'])?></a>
      — <?=htmlspecialchars($t['status'])?> — <?=htmlspecialchars($t['requester'])?></li>
<?php endforeach; ?>
</ul>

<h2>New Ticket</h2>
<form method="post">
  <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
  <input name="title" placeholder="Title" required>
  <input name="category" placeholder="Category" required>
  <select name="priority"><option>Low</option><option>Medium</option><option>High</option></select>
  <textarea name="description" placeholder="Describe the issue" required></textarea>
  <button>Create</button>
</form>
