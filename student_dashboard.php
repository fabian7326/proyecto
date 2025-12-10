<?php
require __DIR__ . '/db.php';
require __DIR__ . '/functions.php';
require_login();
if (($_SESSION['user_role'] ?? '') !== 'STUDENT') {
  header('Location: admin_dashboard.php'); exit;
}
$userId = current_user_id();
$studentId = get_student_id((int)$userId);

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  if ($_POST['action'] === 'add_section') {
    $sid = (int)($_POST['section_id'] ?? 0);
    if ($sid) { add_enrollment($studentId, $sid); set_flash('ok','Sección añadida.'); }
  }
  if ($_POST['action'] === 'generate_invoice') {
    try {
      $invId = generate_invoice($studentId);
      if ($invId) { header('Location: invoice.php?id=' . $invId); exit; }
      else { set_flash('err', 'No tienes secciones pendientes para facturar.'); }
    } catch (Throwable $e) {
      set_flash('err', 'Error al generar factura: ' . $e->getMessage());
    }
  }
}

$ok = flash('ok'); $err = flash('err');

$sections = list_sections();
$pending = student_pending_enrollments($studentId);
$invoices = student_invoices($studentId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Panel del Estudiante</title>
  <link rel="stylesheet" href="styles.css">
</head>

<!-- ✅ agrega esta clase -->
<body class="landing-dashboard">

<?php include __DIR__ . '/partials/header.php'; ?>

<!-- ✅ agrega este wrapper -->
<main class="dashboard-main">
  <div class="container">

    <div class="card">
      <h2>Oferta de secciones</h2>
      <?php if($ok): ?><div class="alert alert-ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
      <?php if($err): ?><div class="alert alert-err"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<table class="dashboard-table">
  <thead>
    <tr>
      <th>Curso</th>
      <th>Sección</th>
      <th>Horario</th>
      <th>Cupos</th>
      <th>Créditos</th>
      <th>Precio</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($sections as $s): ?>
      <tr>
        <td><?= htmlspecialchars($s['course_code'] . ' - ' . $s['course_name']) ?></td>
        <td><?= htmlspecialchars($s['section_code']) ?></td>
        <td><?= weekday_name((int)$s['weekday']) ?> <?= htmlspecialchars($s['start_time']) ?>–<?= htmlspecialchars($s['end_time']) ?></td>
        <td><span class="badge"><?= (int)$s['enrolled_count'] ?>/<?= (int)$s['capacity'] ?></span></td>
        <td><?= (int)$s['credits'] ?></td>
        <td class="col-price">
  <span class="currency">S/</span>
  <span class="amount"><?= cents_to_money((int)$s['price_cents']) ?></span>
</td>

        <td>
          <form method="post" style="display:inline">
            <input type="hidden" name="action" value="add_section">
            <input type="hidden" name="section_id" value="<?= (int)$s['section_id'] ?>">
            <button class="btn btn-accent" type="submit">Añadir</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>


    </div>

    <div class="card">
      <h2>Mis selecciones (pendientes de pago)</h2>
      <?php if (!$pending): ?>
        <div class="small">No tienes secciones por facturar.</div>
      <?php else: ?>
        <table class="dashboard-table">
          <thead>
  <tr>
    <th>Curso</th>
    <th>Sección</th>
    <th>Créditos</th>
    <th>Precio</th>
  </tr>
</thead>
<tbody>
  <?php foreach($pending as $p): ?>
    <tr>
      <td><?= htmlspecialchars($p['course_code'] . ' - ' . $p['course_name']) ?></td>
      <td><?= htmlspecialchars($p['section_code']) ?></td>
      <td><?= (int)$p['credits'] ?></td>
      <td>S/ <?= cents_to_money((int)$p['price_cents']) ?></td>
    </tr>
  <?php endforeach; ?>
</tbody>

        </table>
        <form method="post">
          <input type="hidden" name="action" value="generate_invoice">
          <button class="btn btn-ok" type="submit">Generar factura</button>
        </form>
      <?php endif; ?>
    </div>

    <div class="card">
      <h2>Mis facturas</h2>
      <table class="dashboard-table">
        <thead><tr><th>Número</th><th>Monto</th><th>Estado</th><th>Creada</th><th></th></tr></thead>
        <tbody>
          <?php foreach($invoices as $i): ?>
            <tr>
              <td><?= htmlspecialchars($i['number']) ?></td>
              <td><?= htmlspecialchars($i['currency']) ?> <?= cents_to_money((int)$i['amount_cents']) ?></td>
              <td><span class="badge"><?= htmlspecialchars($i['status']) ?></span></td>
              <td><?= htmlspecialchars($i['created_at']) ?></td>
              <td><a class="btn" href="invoice.php?id=<?= (int)$i['id'] ?>">Ver</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

