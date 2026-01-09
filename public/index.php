<?php
require_once __DIR__ . '/../includes/functions.php';
$pdo = $GLOBALS['pdo'];
// Load categories and products
$cats = $pdo->query('SELECT * FROM product_categories')->fetchAll();
$cat_id = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
if ($cat_id) {
    $stmt = $pdo->prepare('SELECT * FROM product WHERE category_id = ?');
    $stmt->execute([$cat_id]);
    $products = $stmt->fetchAll();
} else {
    $products = $pdo->query('SELECT * FROM product')->fetchAll();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Simple Shop</title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container">
  <aside class="sidebar">
    <h3>Categories</h3>
    <ul>
      <li><a href="/">All</a></li>
      <?php foreach ($cats as $c): ?>
        <li><a href="/?cat=<?=$c['id']?>"><?=htmlspecialchars($c['name'])?></a></li>
      <?php endforeach; ?>
    </ul>
  </aside>
  <main>
    <h1>Products</h1>
    <div class="products">
      <?php foreach ($products as $p): ?>
        <div class="product">
          <h3><?=htmlspecialchars($p['name'])?></h3>
          <p><?=htmlspecialchars($p['description'])?></p>
          <p><strong>$<?=number_format($p['price'],2)?></strong></p>
          <a href="/product.php?id=<?=$p['id']?>">Details</a>
          <form method="post" action="/cart.php" style="display:inline">
            <input type="hidden" name="add_id" value="<?=$p['id']?>">
            <button type="submit">Add to cart</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>