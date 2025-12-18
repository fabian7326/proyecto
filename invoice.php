<?php
require __DIR__ . '/db.php';
require __DIR__ . '/functions.php';
require_login();
$invoiceId = (int)($_GET['id'] ?? 0);
if (!$invoiceId) { http_response_code(400); echo "Missing id"; exit; }

if (($_SESSION['user_role'] ?? '') === 'STUDENT') {
  $studentId = get_student_id((int)current_user_id());
  $invoice = get_invoice($invoiceId, $studentId);
} else {
  $invoice = get_invoice($invoiceId, null);
}
if (!$invoice) { http_response_code(404); echo "No encontrado"; exit; }

$items = invoice_items($invoiceId);
$payment = get_payment_by_invoice($invoiceId);

// Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  if ($_POST['action'] === 'pay_card') {
    header('Location: simulate_card_payment.php?invoice=' . $invoiceId);
    exit;
  }
  if ($_POST['action'] === 'pay_transfer') {
    $p = ensure_transfer_payment($invoiceId);
    header('Location: upload_voucher.php?payment=' . $p['id']);
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Factura <?= htmlspecialchars($invoice['number']) ?></title>
  <link rel="stylesheet" href="styles.css">
</head>

<!-- ✅ misma clase landing -->
<body class="landing-dashboard">

<?php include __DIR__ . '/partials/header.php'; ?>

<!-- ✅ wrapper claro -->
<main class="dashboard-main">
  <div class="container">

    <div class="card invoice-card">
      <h2>Factura <?= htmlspecialchars($invoice['number']) ?></h2>

      <div class="invoice-meta">
        <p><strong>Monto:</strong> <?= htmlspecialchars($invoice['currency']) ?> <?= cents_to_money((int)$invoice['amount_cents']) ?></p>
        <p><strong>Estado:</strong> <span class="badge"><?= htmlspecialchars($invoice['status']) ?></span></p>
      </div>

      <h3>Detalle</h3>
      <table class="dashboard-table">
        <thead>
          <tr><th>Descripción</th><th>Cant.</th><th>Precio unit.</th></tr>
        </thead>
        <tbody>
        <?php foreach($items as $it): ?>
          <tr>
            <td><?= htmlspecialchars($it['description']) ?></td>
            <td><?= (int)$it['qty'] ?></td>
            <td><?= cents_to_money((int)$it['unit_price_cents']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if ($invoice['status'] === 'OPEN' && ($_SESSION['user_role'] ?? '') === 'STUDENT'): ?>
      <div class="card">
  <h3>Opción de pago</h3>

  <div class="row justify-content-start">
    <div class="col-12 text-start">
      <form method="post">
        <input type="hidden" name="action" value="pay_transfer">

        <button class="btn">
          Pago por transferencia (subir comprobante)
        </button>

        <p class="small">El admin verificará tu comprobante.</p>
      </form>
    </div>
  </div>
</div>

    <?php endif; ?>

    <div class="card">
      <h3>Pago asociado</h3>
      <?php if ($payment): ?>
        <table class="dashboard-table">
          <tbody>
            <tr><th>Método</th><td><?= htmlspecialchars($payment['method']) ?></td></tr>
            <tr><th>Estado</th><td><span class="badge"><?= htmlspecialchars($payment['status']) ?></span></td></tr>
            <tr><th>Monto</th><td><?= htmlspecialchars($payment['currency']) ?> <?= cents_to_money((int)$payment['amount_cents']) ?></td></tr>
            <tr><th>Creado</th><td><?= htmlspecialchars($payment['created_at']) ?></td></tr>
          </tbody>
        </table>
      <?php else: ?>
        <p class="small">No hay pago creado aún.</p>
      <?php endif; ?>
    </div>

  </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

