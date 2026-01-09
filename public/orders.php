<?php
require_once __DIR__ . '/../includes/functions.php';
$pdo = $GLOBALS['pdo'];
if (!is_logged_in()) { header('Location: /login.php'); exit; }
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT * FROM `order` WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Your Orders</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <main>
    <h1>Your Orders</h1>
    <?php if (isset($_GET['created'])): ?>
      <p>Order #<?=htmlspecialchars($_GET['created'])?> created.</p>
    <?php endif; ?>
    <?php if (empty($orders)): ?>
      <p>No orders yet.</p>
    <?php else: ?>
      <ul>
        <?php foreach ($orders as $o): ?>
          <li>Order #<?= $o['id'] ?> — <?= $o['created_at'] ?> — <?= htmlspecialchars($o['status']) ?>
            <ul>
            <?php
              $s = $pdo->prepare('SELECT oi.*, p.name FROM order_items oi JOIN product p ON p.id = oi.product_id WHERE oi.order_id = ?');
              $s->execute([$o['id']]);
              $items = $s->fetchAll();
              foreach ($items as $it) echo '<li>'.htmlspecialchars($it['name']).' x '.$it['quantity'].' ($'.number_format($it['price'],2).')</li>';
            ?>
            </ul>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>