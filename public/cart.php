<?php
require_once __DIR__ . '/../includes/functions.php';
$pdo = $GLOBALS['pdo'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_id'])) {
        $id = (int)$_POST['add_id'];
        $qty = isset($_POST['qty']) ? max(1,(int)$_POST['qty']) : 1;
        add_to_cart($id, $qty);
        header('Location: /cart.php');
        exit;
    }
    if (isset($_POST['update'])) {
        foreach ($_POST['qty'] as $pid => $q) {
            update_cart((int)$pid, (int)$q);
        }
        header('Location: /cart.php');
        exit;
    }
}
$items = get_cart_items($pdo);
$total = 0;
foreach ($items as $it) $total += $it['price'] * $it['quantity'];
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Cart</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <main>
    <h1>Your Cart</h1>
    <?php if (empty($items)): ?>
      <p>Your cart is empty.</p>
    <?php else: ?>
      <form method="post">
        <table>
          <tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr>
          <?php foreach ($items as $it): ?>
            <tr>
              <td><?=htmlspecialchars($it['name'])?></td>
              <td>$<?=number_format($it['price'],2)?></td>
              <td><input type="number" name="qty[<?=$it['id']?>]" value="<?=$it['quantity']?>" min="0"></td>
              <td>$<?=number_format($it['price']*$it['quantity'],2)?></td>
            </tr>
          <?php endforeach; ?>
        </table>
        <p><strong>Total: $<?=number_format($total,2)?></strong></p>
        <button type="submit" name="update">Update cart</button>
      </form>
      <p><a href="/checkout.php">Proceed to checkout</a></p>
    <?php endif; ?>
  </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>