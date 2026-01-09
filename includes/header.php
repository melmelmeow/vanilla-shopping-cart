<?php
require_once __DIR__ . '/functions.php';
$u = null; if (is_logged_in()) $u = current_user($pdo);
?>
<header class="site-header">
  <div class="inner">
    <h2><a href="/">Simple Shop</a></h2>
    <nav>
      <a href="/cart.php">Cart (<?=cart_count()?>)</a>
      <?php if ($u): ?>
        <a href="/orders.php">Orders</a>
        <span><?=htmlspecialchars($u['email'])?></span>
        <a href="/logout.php">Logout</a>
      <?php else: ?>
        <a href="/register.php">Register</a>
        <a href="/login.php">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>