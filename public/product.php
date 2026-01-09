<?php
require_once __DIR__ . '/../includes/functions.php';
$pdo = $GLOBALS['pdo'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM product WHERE id = ?');
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) {
    header('HTTP/1.0 404 Not Found');
    echo 'Product not found';
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title><?=htmlspecialchars($p['name'])?></title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <main>
    <h1><?=htmlspecialchars($p['name'])?></h1>
    <p><?=nl2br(htmlspecialchars($p['description']))?></p>
    <p><strong>$<?=number_format($p['price'],2)?></strong></p>
    <form method="post" action="/cart.php">
      <input type="hidden" name="add_id" value="<?=$p['id']?>">
      <input type="number" name="qty" value="1" min="1">
      <button type="submit">Add to cart</button>
    </form>
  </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>