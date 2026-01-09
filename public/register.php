<?php
require_once __DIR__ . '/../includes/functions.php';
$pdo = $GLOBALS['pdo'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $name = trim($_POST['name']);
    $password = $_POST['password'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Invalid email';
    else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare('INSERT INTO users (email, password, name) VALUES (?, ?, ?)');
            $stmt->execute([$email, $hash, $name]);
            $id = $pdo->lastInsertId();
            $_SESSION['user_id'] = $id;
            header('Location: /'); exit;
        } catch (Exception $e) {
            $error = 'Could not register (email maybe taken)';
        }
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Register</title><link rel="stylesheet" href="/assets/style.css"></head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container"><main>
<h1>Register</h1>
<?php if ($error) echo '<p class="error">'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
  <label>Email: <input type="email" name="email" required></label><br>
  <label>Name: <input type="text" name="name"></label><br>
  <label>Password: <input type="password" name="password" required></label><br>
  <button type="submit">Register</button>
</form>
</main></div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body></html>