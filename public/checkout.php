<?php
require_once __DIR__ . '/../includes/functions.php';
$pdo = $GLOBALS['pdo'];
if (!is_logged_in()) {
    header('Location: /login.php'); exit;
}
$items = get_cart_items($pdo);
if (empty($items)) { header('Location: /cart.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    try {
        $order_id = create_order($pdo, $user_id, $items);
        unset($_SESSION['cart']);
        header('Location: /orders.php?created='.$order_id);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
$total = array_reduce($items, fn($s,$i)=>$s+($i['price']*$i['quantity']), 0);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Checkout</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <main>
    <h1>Checkout</h1>
    <?php if (!empty($error)) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
    <h3>Order summary</h3>
    <ul>
      <?php foreach ($items as $it): ?>
        <li><?=htmlspecialchars($it['name'])?> x <?=$it['quantity']?> — $<?=number_format($it['price']*$it['quantity'],2)?></li>
      <?php endforeach; ?>
    </ul>
    <p><strong>Total: $<?=number_format($total,2)?></strong></p>
    <form method="post">
      <button type="submit">Place order</button>
    </form>
  </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>