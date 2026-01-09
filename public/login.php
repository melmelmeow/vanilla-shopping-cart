<?php
require_once __DIR__ . '/../includes/functions.php';
$pdo = $GLOBALS['pdo'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    if (!$u) $error = 'Invalid credentials';
    else {
        $ok = false;
        if (password_verify($password, $u['password'])) $ok = true;
        elseif ($password === $u['password']) {
            $ok = true; // plain text password match, upgrade hash 
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$newHash, $u['id']]);
        }
        if ($ok) {
            $_SESSION['user_id'] = $u['id'];
            header('Location: /'); exit;
        } else $error = 'Invalid credentials';
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Login</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container"><main>
<h1>Login</h1>
<?php if ($error) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
  <label>Email: <input type="email" name="email" required></label><br>
  <label>Password: <input type="password" name="password" required></label><br>
  <button type="submit">Login</button>
</form>
</main></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body></html>