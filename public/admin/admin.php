<?php
// Admin page for Simple Shop
require_once __DIR__ . '/../../includes/functions.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_user'])) {
        $user_id = (int)$_POST['user_id'];
        if ($user_id !== $_SESSION['user_id']) { 
            $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$user_id]);
            $message = 'User deleted.';
        } else {
            $message = 'Cannot delete yourself.';
        }
    } elseif (isset($_POST['update_order_status'])) {
        $order_id = (int)$_POST['order_id'];
        $status = trim($_POST['status']);
        $valid_statuses = ['pending', 'shipped', 'delivered', 'cancelled'];
        if (in_array($status, $valid_statuses)) {
            $pdo->prepare('UPDATE `order` SET status = ? WHERE id = ?')->execute([$status, $order_id]);
            $message = 'Order status updated.';
        } else {
            $message = 'Invalid status.';
        }
    } elseif (isset($_POST['delete_product'])) {
        $product_id = (int)$_POST['product_id'];
        $pdo->prepare('DELETE FROM product WHERE id = ?')->execute([$product_id]);
        $message = 'Product deleted.';
    } elseif (isset($_POST['add_product'])) {
        $name = trim($_POST['name']);
        $price = (float)$_POST['price'];
        $description = trim($_POST['description']);
        if (!empty($name) && $price > 0) {
            $pdo->prepare('INSERT INTO product (name, price, description) VALUES (?, ?, ?)')->execute([$name, $price, $description]);
            $message = 'Product added.';
        } else {
            $message = 'Invalid product data.';
        }
    }
}

// Fetch data
$users = $pdo->query('SELECT id, email, name, role FROM users ORDER BY id DESC')->fetchAll();
$orders = $pdo->query('SELECT o.id, o.created_at, o.status, u.email FROM `order` o JOIN users u ON u.id = o.user_id ORDER BY o.created_at DESC')->fetchAll();
$products = $pdo->query('SELECT id, name, price, description FROM product ORDER BY id DESC')->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Panel - Simple Shop</title>
    <link rel="stylesheet" href="/assets/style.css">
    <style>
        .message { color: green; }
        .error { color: red; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        form { margin-bottom: 1rem; }
        .section { margin-bottom: 2rem; }
    </style>
</head>
<body>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<div class="container">
    <main>
        <h1>Admin Panel</h1>
        <?php if ($message): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <section class="section">
            <h2>Users</h2>
            <table>
                <thead>
                    <tr><th>ID</th><th>Email</th><th>Name</th><th>Role</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['role']) ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" name="delete_user" onclick="return confirm('Delete user?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="section">
            <h2>Orders</h2>
            <table>
                <thead>
                    <tr><th>ID</th><th>User Email</th><th>Created At</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['email']) ?></td>
                            <td><?= $order['created_at'] ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <select name="status">
                                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                        <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="update_order_status">Update</button>
                                </form>
                            </td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="section">
            <h2>Products</h2>
            <form method="post">
                <label>Name: <input type="text" name="name" required></label>
                <label>Price: <input type="number" step="0.01" name="price" required></label>
                <label>Description: <textarea name="description"></textarea></label>
                <button type="submit" name="add_product">Add Product</button>
            </form>
            <table>
                <thead>
                    <tr><th>ID</th><th>Name</th><th>Price</th><th>Description</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td>$<?= number_format($product['price'], 2) ?></td>
                            <td><?= htmlspecialchars($product['description']) ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <button type="submit" name="delete_product" onclick="return confirm('Delete product?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>