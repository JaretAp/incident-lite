<?php
require __DIR__.'/../app/db.php';
require __DIR__.'/../app/auth.php';
require __DIR__.'/../app/csrf.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$ticket = $pdo->prepare('SELECT t.*, u.name requester FROM tickets t JOIN users u ON u.id=t.user_id WHERE t.id=?');
$ticket->execute([$id]); $ticket = $ticket->fetch() ?: exit('Not found');

if ($_SERVER['REQUEST_METHOD']==='POST') {
  verify_csrf($_POST['csrf'] ?? '');
  if (isset($_POST['comment'])) {
    $stmt = $pdo->prepare('INSERT INTO comments (ticket_id,user_id,body,is_internal) VALUES (?,?,?,?)');
    $stmt->execute([$id, current_user()['id'], $_POST['comment'], (int)isset($_POST['internal'])]);
  }
  if (is_agent() && isset($_POST['status'])) {
    $stmt = $pdo->prepare('UPDATE tickets SET status=? WHERE id=?');
    $stmt->execute([$_POST['status'], $id]);
  }
  header("Location: ticket.php?id=$id"); exit;
}

$comments = $pdo->prepare('SELECT c.*, u.name FROM comments c JOIN users u ON u.id=c.user_id WHERE ticket_id=? ORDER BY c.created_at');
$comments->execute([$id]);
?>
<h1><?=htmlspecialchars($ticket['title'])?></h1>
<p>Status: <?=$ticket['status']?> • Priority: <?=$ticket['priority']?> • By: <?=htmlspecialchars($ticket['requester'])?></p>
<p><?=nl2br(htmlspecialchars($ticket['description']))?></p>

<h2>Comments</h2>
<ul>
<?php foreach($comments as $c): ?>
  <li><strong><?=htmlspecialchars($c['name'])?></strong>
      <?= $c['is_internal'] ? '<em>(internal)</em>' : '' ?> —
      <?= nl2br(htmlspecialchars($c['body'])) ?></li>
<?php endforeach; ?>
</ul>

<form method="post">
  <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
  <textarea name="comment" required placeholder="Add a comment"></textarea>
  <?php if (is_agent()): ?><label><input type="checkbox" name="internal"> Internal</label><?php endif; ?>
  <button>Post</button>
</form>

<?php if (is_agent()): ?>
<form method="post" style="margin-top:1rem">
  <input type="hidden" name="csrf" value="<?=htmlspecialchars(csrf_token())?>">
  <select name="status">
    <?php foreach (['Open','In Progress','Resolved','Closed'] as $s): ?>
      <option <?=$ticket['status']===$s?'selected':''?>><?=$s?></option>
    <?php endforeach; ?>
  </select>
  <button>Update Status</button>
</form>
<?php endif; ?>
