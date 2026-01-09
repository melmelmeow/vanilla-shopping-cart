<?php
session_start();
require_once __DIR__ . '/db.php';

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function current_user($pdo = null) {
    if (!is_logged_in()) return null;
    if ($pdo === null) {
        global $pdo;
        if (empty($pdo)) return null;
    }
    $stmt = $pdo->prepare('SELECT id, email, name, role FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function is_admin() {
    global $pdo;
    if (!is_logged_in()) return false;
    $user_id = $_SESSION['user_id'];
    if (empty($pdo)) return false;
    $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    return $user && isset($user['role']) && $user['role'] === 'admin';
}

// Admin helpers for admin login page
function is_admin_logged_in() {
    return !empty($_SESSION['admin_id']);
}

function current_admin($pdo = null) {
    if (!is_admin_logged_in()) return null;
    if ($pdo === null) {
        global $pdo;
        if (empty($pdo)) return null;
    }
    $stmt = $pdo->prepare('SELECT id, email, name FROM admin WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    return $stmt->fetch();
}

function admin_login($pdo = null, $email, $password) {
    if ($pdo === null) {
        global $pdo;
        if (empty($pdo)) return false;
    }
    $stmt = $pdo->prepare('SELECT id, email, password FROM admin WHERE email = ?');
    $stmt->execute([$email]);
    $row = $stmt->fetch();
    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['admin_id'] = $row['id'];
        return true;
    }
    return false;
}

function admin_logout() {
    unset($_SESSION['admin_id']);
}

function add_to_cart($product_id, $qty = 1) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty;
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }
}

function update_cart($product_id, $qty) {
    if (!isset($_SESSION['cart'])) return;
    if ($qty <= 0) unset($_SESSION['cart'][$product_id]);
    else $_SESSION['cart'][$product_id] = $qty;
}

function cart_count() {
    if (!isset($_SESSION['cart'])) return 0;
    return array_sum($_SESSION['cart']);
}

function get_cart_items($pdo) {
    if (empty($_SESSION['cart'])) return [];
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM product WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $rows = $stmt->fetchAll();
    $out = [];
    foreach ($rows as $r) {
        $r['quantity'] = $_SESSION['cart'][$r['id']];
        $out[] = $r;
    }
    return $out;
}

function create_order($pdo, $user_id, $items) {
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO `order` (user_id) VALUES (?)");
        $stmt->execute([$user_id]);
        $order_id = $pdo->lastInsertId();
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($items as $it) {
            $stmtItem->execute([$order_id, $it['id'], $it['quantity'], $it['price']]);
        }
        $pdo->commit();
        return $order_id;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

?>