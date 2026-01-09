<?php
require_once __DIR__ . '/../includes/functions.php';

// ensure PDO is available
if (!isset($GLOBALS['pdo'])) {
    require_once __DIR__ . '/../includes/db.php';
}
$pdo = $GLOBALS['pdo'] ?? null;
if (!$pdo) {
    die('Database connection not available. Check includes/db.php');
}

if (!is_logged_in()) { header('Location: /login.php'); exit; }
$user_id = $_SESSION['user_id'];

$error = '';

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order'])) {
    $order_id = (int)($_POST['order_id'] ?? 0);
    if ($order_id <= 0) {
        $error = 'Invalid order id.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM `order` WHERE id = ? AND user_id = ?');
        $stmt->execute([$order_id, $user_id]);
        if ($stmt->fetch()) {
            // delete related items first (if no FK cascade)
            $pdo->prepare('DELETE FROM order_items WHERE order_id = ?')->execute([$order_id]);
            $pdo->prepare('DELETE FROM `order` WHERE id = ?')->execute([$order_id]);
            header('Location: /orders.php?deleted=' . $order_id);
            exit;
        } else {
            $error = 'Order not found or access denied.';
        }
    }
}

// Fetch user orders
$stmt = $pdo->prepare('SELECT id, created_at, status FROM `order` WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Orders</title>
    <link rel="stylesheet" href="/assets/style.css">
    <style>
        .error { color: red; }
        .delete-form { display: inline; margin-left: 10px; }
        .delete-btn { background: #f44336; color: white; border: none; padding: 5px 10px; cursor: pointer; }
        .delete-btn:hover { background: #d32f2f; }
    </style>
    <script>
        function confirmDelete(orderId) {
            return confirm('Are you sure you want to delete Order #' + orderId + '? This action cannot be undone.');
        }
    </script>
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <main>
    <h1>Your Orders</h1>
    <?php if (isset($_GET['created'])): ?>
      <p>Order #<?=htmlspecialchars($_GET['created'])?> created.</p>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
      <p>Order #<?=htmlspecialchars($_GET['deleted'])?> deleted successfully.</p>
    <?php endif; ?>
    <?php if ($error): ?>
      <p class="error"><?=htmlspecialchars($error)?></p>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
      <p>No orders yet.</p>
    <?php else: ?>
      <ul>
        <?php foreach ($orders as $o): ?>
          <li>
            Order #<?= htmlspecialchars($o['id']) ?> — <?= htmlspecialchars($o['created_at']) ?> — <?= htmlspecialchars($o['status']) ?>
            <form class="delete-form" method="post" onsubmit="return confirmDelete(<?= (int)$o['id'] ?>)">
              <input type="hidden" name="order_id" value="<?= (int)$o['id'] ?>">
              <button type="submit" name="delete_order" class="delete-btn">Delete Order</button>
            </form>
            <ul>
            <?php
              $s = $pdo->prepare('SELECT oi.quantity, oi.price, p.name FROM order_items oi JOIN product p ON p.id = oi.product_id WHERE oi.order_id = ?');
              $s->execute([$o['id']]);
              $items = $s->fetchAll();
              foreach ($items as $it) {
                  echo '<li>'.htmlspecialchars($it['name']).' x '.(int)$it['quantity'].' ($'.number_format($it['price'],2).')</li>';
              }
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