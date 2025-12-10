<?php
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';

  $stmt = db()->prepare('SELECT id,email,password_hash,role FROM users WHERE email = ? LIMIT 1');
  $stmt->execute([$email]);
  $u = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($u && password_verify($pass, $u['password_hash'])) {
    $_SESSION['user_id'] = $u['id'];
    $_SESSION['user_email'] = $u['email'];
    $_SESSION['user_role'] = $u['role'];

    if ($u['role'] === 'ADMIN') {
      header('Location: admin_dashboard.php'); exit;
    } else {
      header('Location: student_dashboard.php'); exit;
    }
  } else {
    set_flash('err','Credenciales inválidas');
  }
}

$err = flash('err');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Ingresar</title>

  <link href="img/logo.png" rel="icon">
  <link rel="stylesheet" href="styles.css">
</head>

<body class="landing-login">

  <!-- Navbar simple -->
  <header class="landing-nav">
    <div class="landing-nav__container">
      <a href="index.php" class="landing-brand">
        <img src="img/logo.png" alt="logo" class="landing-brand__logo">
        <span class="landing-brand__text">Educación KESENFA</span>
      </a>

      <nav class="landing-menu">
        <a href="login.php" class="landing-menu__link">Ingresar</a>
        <a href="register.php" class="landing-menu__btn">Regístrate</a>
      </nav>
    </div>
  </header>

  <!-- Card login -->
  <main class="landing-hero">
    <div class="landing-hero__overlay"></div>

    <div class="landing-login-card">
      <h2>Ingresar</h2>

      <?php if(!empty($err)): ?>
        <div class="alert alert-err"><?= htmlspecialchars($err) ?></div>
      <?php endif; ?>

      <form method="post" class="landing-form">
        <label>Correo</label>
        <input type="email" name="email" required placeholder="correo@ejemplo.com">

        <label>Contraseña</label>
        <input type="password" name="password" required placeholder="••••••••">

        <div class="landing-actions">
          <button class="landing-btn-primary" type="submit">Entrar</button>
          <a class="landing-btn-secondary" href="register.php">Crear cuenta</a>
        </div>
      </form>

      <p class="landing-small">
        Sistema de Gestión de Matrícula – Educación KESENFA
      </p>
    </div>
  </main>

  <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
