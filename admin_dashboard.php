<?php
require __DIR__ . '/db.php';
require __DIR__ . '/functions.php';
require_role('ADMIN');

$pdo = db();

// Actions: verify/reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $pid = (int)($_POST['payment_id'] ?? 0);
  $payment = get_payment($pid);
  if (!$payment) { set_flash('err','Pago no encontrado.'); header('Location: admin_dashboard.php'); exit; }

  if ($_POST['action'] === 'verify') {
    $pdo->beginTransaction();
    try {
      $pdo->prepare('UPDATE payments SET status="SUCCEEDED", verified_by=?, verified_at=NOW() WHERE id=?')
          ->execute([current_user_id(), $pid]);
      mark_invoice_paid($payment['invoice_id']);
      confirm_enrollments_for_invoice($payment['invoice_id']);
      $pdo->commit();
      set_flash('ok','Pago verificado y matrícula confirmada.');
    } catch (Throwable $e) {
      if ($pdo->inTransaction()) $pdo->rollBack();
      set_flash('err','Error: ' . $e->getMessage());
    }
  } elseif ($_POST['action'] === 'reject') {
    $pdo->prepare('UPDATE payments SET status="FAILED", verified_by=?, verified_at=NOW() WHERE id=?')
        ->execute([current_user_id(), $pid]);
    set_flash('ok','Pago rechazado.');
  }

  header('Location: admin_dashboard.php'); exit;
}

$ok  = flash('ok');
$err = flash('err');

// Pending verifications
$pending = $pdo->query('
  SELECT p.*, i.number AS invoice_number, u.email AS student_email
  FROM payments p
  JOIN invoices i ON i.id = p.invoice_id
  JOIN students st ON st.id = i.student_id
  JOIN users u ON u.id = st.user_id
  WHERE p.status = "REVIEW"
  ORDER BY p.created_at ASC
')->fetchAll(PDO::FETCH_ASSOC);

// Recent payments
$recent = $pdo->query('
  SELECT p.*, i.number AS invoice_number, u.email AS student_email
  FROM payments p
  JOIN invoices i ON i.id = p.invoice_id
  JOIN students st ON st.id = i.student_id
  JOIN users u ON u.id = st.user_id
  ORDER BY p.created_at DESC LIMIT 20
')->fetchAll(PDO::FETCH_ASSOC);

// Cursos/Secciones para administrar
$cursosAdmin = $pdo->query("
  SELECT c.id, c.code, c.name, s.id AS section_id, s.code AS section_code,
         s.weekday, s.start_time, s.end_time, s.capacity
  FROM courses c
  JOIN sections s ON s.course_id = c.id
  ORDER BY c.code
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <link href="img/logo.png" rel="icon">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin</title>
  <link rel="stylesheet" href="styles.css">
</head>

<!-- ✅ MISMO ESTILO LANDING -->
<body class="landing-dashboard">

<?php include __DIR__ . '/partials/header.php'; ?>

<!-- ✅ MISMO WRAPPER -->
<main class="dashboard-main">
  <div class="container">

    <!-- ============ PAGOS POR VERIFICAR ============ -->
    <div class="card">
      <h2>Pagos por verificar (transferencias)</h2>

      <?php if($ok): ?><div class="alert alert-ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
      <?php if($err): ?><div class="alert alert-err"><?= htmlspecialchars($err) ?></div><?php endif; ?>

      <?php if(!$pending): ?>
        <div class="small">No hay pagos en revisión.</div>
      <?php else: ?>
        <table class="dashboard-table">
          <thead>
            <tr>
              <th>Factura</th>
              <th>Alumno</th>
              <th>Monto</th>
              <th>Creado</th>
              <th>Evidencia</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($pending as $p): ?>
              <tr>
                <td>
                  <a href="invoice.php?id=<?= (int)$p['invoice_id'] ?>">
                    <?= htmlspecialchars($p['invoice_number']) ?>
                  </a>
                </td>
                <td><?= htmlspecialchars($p['student_email']) ?></td>
                <td><?= htmlspecialchars($p['currency']) ?> <?= cents_to_money((int)$p['amount_cents']) ?></td>
                <td><?= htmlspecialchars($p['created_at']) ?></td>
                <td>
                  <?php
                    $docs = $pdo->prepare('SELECT * FROM documents WHERE payment_id = ?');
                    $docs->execute([$p['id']]);
                    $ds = $docs->fetchAll(PDO::FETCH_ASSOC);

                    if (!$ds) {
                      echo "<span class='small'>Sin archivo</span>";
                    } else {
                      foreach ($ds as $d) {
                        $url = 'uploads/' . rawurlencode($d['path']);
                        echo "<a href='{$url}' target='_blank' class='btn'>Ver</a> ";
                      }
                    }
                  ?>
                </td>
                <td>
                  <form method="post" style="display:inline">
                    <input type="hidden" name="payment_id" value="<?= (int)$p['id'] ?>">
                    <button class="btn btn-ok" name="action" value="verify">Verificar</button>
                  </form>
                  <form method="post" style="display:inline">
                    <input type="hidden" name="payment_id" value="<?= (int)$p['id'] ?>">
                    <button class="btn btn-warn" name="action" value="reject">Rechazar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

    <!-- ============ PAGOS RECIENTES ============ -->
    <div class="card">
      <h2>Pagos recientes</h2>

<table class="dashboard-table admin-payments">
  <thead>
    <tr>
      <th class="col-invoice">Factura</th>
      <th class="col-student">Alumno</th>
      <th class="col-method">Método</th>
      <th class="col-status">Estado</th>
      <th class="col-amount">Monto</th>
      <th class="col-date">Fecha y Hora</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($recent as $p): ?>
      <tr>
        <td class="col-invoice">
          <a href="invoice.php?id=<?= (int)$p['invoice_id'] ?>" class="invoice-link">
            <?= htmlspecialchars($p['invoice_number']) ?>
          </a>
        </td>
        <td class="col-student"><?= htmlspecialchars($p['student_email']) ?></td>
        <td class="col-method"><?= htmlspecialchars($p['method']) ?></td>
        <td class="col-status"><span class="badge"><?= htmlspecialchars($p['status']) ?></span></td>
        <td class="col-amount"><?= htmlspecialchars($p['currency']) ?> <?= cents_to_money((int)$p['amount_cents']) ?></td>
        <td class="col-date"><?= htmlspecialchars($p['created_at']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

    </div>

    <!-- ============ ADMINISTRAR CURSOS/SECCIONES ============ -->
    <div class="card">
      <h2>Administrar oferta de cursos</h2>

      <?php if(!$cursosAdmin): ?>
        <div class="small">No hay cursos cargados.</div>
      <?php else: ?>
        <table class="dashboard-table">
          <thead>
            <tr>
              <th>Curso</th>
              <th>Sección</th>
              <th>Horario</th>
              <th>Cupos</th>
              <th>Editar</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($cursosAdmin as $c): ?>
              <tr>
                <td><?= htmlspecialchars($c['code']." - ".$c['name']) ?></td>
                <td><?= htmlspecialchars($c['section_code']) ?></td>
                <td><?= htmlspecialchars($c['start_time']." - ".$c['end_time']) ?></td>
                <td><?= htmlspecialchars($c['capacity']) ?></td>
                <td>
                  <form action="editar_seccion.php" method="get">
                    <input type="hidden" name="id" value="<?= (int)$c['section_id'] ?>">
                    <button class="btn">Editar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>

    </div>

    <!-- ============ MANTENIMIENTO DE CURSOS ============ -->
    <div class="card">
      <h2>Mantenimiento de cursos</h2>
      <p class="small">Desde aquí puedes editar nombre, precio y (opcionalmente) imagen de los cursos que aparecen en la vitrina.</p>
      <a class="btn btn-accent" href="mantenimiento/cursos.php">Editar cursos</a>
    </div>

  </div>
</main>


  </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
