<?php
session_start();

// Jika sudah login, langsung ke menu
if (isset($_SESSION['user'])) {
    header('Location: menu.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === 'admin' && $password === 'admin123') {
        session_regenerate_id(true); // cegah session fixation
        $_SESSION['user'] = $username;
        header('Location: menu.php');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ALDOMART – Login</title>
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Sora:wght@600;700&display=swap" rel="stylesheet">
</head>
<body class="login-page">

<div class="login-wrapper">
  <div class="login-card">

    <div class="brand">
      <div class="brand-icon">🛒</div>
      <h1>ALDOMART</h1>
      <p>Silakan masuk untuk melanjutkan</p>
    </div>

    <form method="POST" action="">

      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username"
               placeholder="Masukkan username"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
               required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="password-wrap">
          <input type="password" id="password" name="password"
                 placeholder="Masukkan password" required>
          <button type="button" class="toggle-pw" onclick="togglePw()" aria-label="Tampilkan password">
            <svg id="eye-icon" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
          </button>
        </div>
      </div>

      <?php if ($error): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <button type="submit" class="btn-login">Masuk</button>

    </form>

  </div>
</div>

<script src="login.js"></script>
</body>
</html>
