<?php
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $full_name = trim($_POST['full_name'] ?? '');
  $doc_id = trim($_POST['doc_id'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  if (!$full_name || !$email || !$pass) {
    set_flash('err', 'Completa todos los campos obligatorios.');
  } else {
    try {
      $pdo = db();
      $pdo->beginTransaction();
      $stmt = $pdo->prepare('INSERT INTO users(email,password_hash,role) VALUES(?,?,?)');
      $stmt->execute([$email, password_hash($pass, PASSWORD_DEFAULT), 'STUDENT']);
      $userId = (int)$pdo->lastInsertId();
      $stmt2 = $pdo->prepare('INSERT INTO students(user_id, full_name, doc_id) VALUES(?,?,?)');
      $stmt2->execute([$userId, $full_name, $doc_id]);
      $pdo->commit();
      set_flash('ok','Cuenta creada. Ya puedes iniciar sesión.');
      header('Location: index.php'); exit;
    } catch (Throwable $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      set_flash('err','Error al registrar: ' . $e->getMessage());
    }
  }
}
$ok = flash('ok'); $err = flash('err');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Registro Kesenfa</title>

  <!-- Favicon -->
  <link href="img/logo.png" rel="icon">

  <link rel="stylesheet" href="styles.css">
</head>

<!-- ✅ mismo estilo landing que login -->
<body class="landing-login">

  <!-- NAVBAR estilo index.html -->
  <header class="landing-nav">
    <div class="landing-nav__container">
      <a href="index.html" class="landing-brand">
        <img src="img/logo.png" alt="logo" class="landing-brand__logo">
        <span class="landing-brand__text">Educación KESENFA</span>
      </a>

      <nav class="landing-menu">
        <a href="index.php" class="landing-menu__link">Ingresar</a>
        <a href="register.php" class="landing-menu__btn">Regístrate</a>
      </nav>
    </div>
  </header>

  <main class="landing-hero">
    <div class="landing-hero__overlay"></div>

    <div class="landing-login-card">
      <h2>Crear cuenta de estudiante</h2>

      <?php if($ok): ?>
        <div class="alert alert-ok"><?= htmlspecialchars($ok) ?></div>
      <?php endif; ?>
      <?php if($err): ?>
        <div class="alert alert-err"><?= htmlspecialchars($err) ?></div>
      <?php endif; ?>

      <form method="post" class="landing-form">
        <label>Nombre completo</label>
        <input name="full_name" required placeholder="Ingresa tu nombre completo">

        <label>DNI</label>
        <input name="doc_id" placeholder="Opcional">

        <label>Correo</label>
        <input type="email" name="email" required placeholder="correo@ejemplo.com">

        <label>Contraseña</label>
        <input type="password" name="password" required placeholder="••••••••">

        <div class="landing-actions">
          <button class="landing-btn-primary" type="submit">Registrarme</button>
          <a class="landing-btn-secondary" href="index.php">Volver</a>
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
