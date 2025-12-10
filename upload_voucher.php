<?php
require __DIR__ . '/db.php';
require __DIR__ . '/functions.php';
require_login();
if (($_SESSION['user_role'] ?? '') !== 'STUDENT') { http_response_code(403); exit; }
$paymentId = (int)($_GET['payment'] ?? 0);
$p = get_payment($paymentId);
if (!$p) { http_response_code(404); echo "Pago no encontrado"; exit; }

$err = flash('err'); $ok = flash('ok');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($_FILES['voucher']) || $_FILES['voucher']['error'] !== UPLOAD_ERR_OK) {
    set_flash('err','Archivo no válido.');
    header('Location: upload_voucher.php?payment=' . $paymentId); exit;
  }
  $file = $_FILES['voucher'];
  $allowed = ['image/jpeg','image/png','application/pdf'];
  if (!in_array($file['type'], $allowed)) {
    set_flash('err','Formato no permitido. Usa JPG, PNG o PDF.');
    header('Location: upload_voucher.php?payment=' . $paymentId); exit;
  }
  $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
  $safeName = 'voucher_' . $paymentId . '_' . time() . '.' . $ext;
  $dest = __DIR__ . '/uploads/' . $safeName;
  if (!move_uploaded_file($file['tmp_name'], $dest)) {
    set_flash('err','No se pudo guardar el archivo.'); header('Location: upload_voucher.php?payment=' . $paymentId); exit;
  }
  // Store document & set payment to REVIEW
  $stmt = db()->prepare('INSERT INTO documents(payment_id, uploader_id, filename, mime_type, path) VALUES(?,?,?,?,?)');
  $mime = $file['type'];
  $stmt->execute([$paymentId, current_user_id(), $file['name'], $mime, $safeName]);
  db()->prepare('UPDATE payments SET status = "REVIEW", method = "TRANSFER", provider = "TRANSFER" WHERE id = ?')->execute([$paymentId]);

  set_flash('ok','Comprobante subido. Un administrador revisará tu pago.');
  header('Location: invoice.php?id=' . $p['invoice_id']); exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Subir comprobante</title>
  <link rel="stylesheet" href="styles.css">
</head>

<!-- ✅ misma clase landing -->
<body class="landing-dashboard">

<?php include __DIR__ . '/partials/header.php'; ?>

<!-- ✅ wrapper claro -->
<main class="dashboard-main">
  <div class="container">
    <div class="card upload-card">
      <h2>Subir comprobante</h2>

      <?php if($ok): ?>
        <div class="alert alert-ok"><?= htmlspecialchars($ok) ?></div>
      <?php endif; ?>
      <?php if($err): ?>
        <div class="alert alert-err"><?= htmlspecialchars($err) ?></div>
      <?php endif; ?>

      <p class="upload-meta">
        Factura #<?= htmlspecialchars(get_invoice($p['invoice_id'])['number']) ?>
        — Monto: <?= htmlspecialchars($p['currency']) ?> <?= cents_to_money((int)$p['amount_cents']) ?>
      </p>

      <form method="post" enctype="multipart/form-data" class="upload-form">
        <label>Archivo (JPG, PNG o PDF)</label>
        <input type="file" name="voucher" accept=".jpg,.jpeg,.png,.pdf" required>

        <div class="landing-actions">
          <button class="btn btn-ok" type="submit">Subir</button>
          <a class="btn" href="invoice.php?id=<?= (int)$p['invoice_id'] ?>">Volver</a>
        </div>
      </form>

      <p class="small">
        Los comprobantes se guardan en <code>/uploads/</code>. No se procesan datos bancarios.
      </p>
    </div>
  </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

