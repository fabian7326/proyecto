<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$userEmail = $_SESSION['user_email'] ?? null;
$userRole  = $_SESSION['user_role'] ?? null;

// ✅ base absoluta de tu proyecto
$BASE = "/tarazona/cursos";
?>
<header>
  <div class="wrap">
    <div>
      <strong>SGM-PHP</strong>
      <span class="small">Sistema de Gestión de Matrícula</span>
    </div>

    <nav>
      <?php if ($userEmail): ?>
        <span class="small">
          <?= htmlspecialchars($userEmail) ?> (<?= htmlspecialchars($userRole) ?>)
        </span>

        <!-- ✅ rutas absolutas -->
        <?php if ($userRole === 'STUDENT'): ?>
  <a href="<?= $BASE ?>/student_dashboard.php">Estudiante</a>
<?php endif; ?>


        <?php if ($userRole === 'ADMIN'): ?>
          <a href="<?= $BASE ?>/admin_dashboard.php">Admin</a>
        <?php endif; ?>

        <a href="<?= $BASE ?>/logout.php">Salir</a>

      <?php else: ?>
        <a href="<?= $BASE ?>/index.php">Ingresar</a>
        <a href="<?= $BASE ?>/register.php">Registrarme</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
