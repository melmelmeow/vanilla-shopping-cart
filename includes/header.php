<?php
require_once __DIR__ . '/functions.php';
$u = null;
$cart_count = 0;
try {
    if (is_logged_in()) {
        $u = current_user($pdo);
    }
   
} catch (Exception $e) {
    error_log('Header error: ' . $e->getMessage());
    $cart_count = 0;
}
?>
<header class="site-header" role="banner">
  <div class="inner">
    <h1><a href="/" aria-label="Simple Shop Homepage">Simple Shop</a></h1>
    <nav role="navigation" aria-label="Main navigation">
      <a href="/cart.php" aria-label="View cart with <?= $cart_count ?> items">Cart (<?= $cart_count ?>)</a>
      <?php if ($u): ?>
        <a href="/orders.php">Orders</a>
        <span>Welcome, <?= htmlspecialchars($u['email']) ?></span>
        <a href="/logout.php">Logout</a>
      <?php else: ?>
        <a href="/register.php">Register</a>
        <a href="/login.php">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>