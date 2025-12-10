<?php
require __DIR__ . '/db.php';
require __DIR__ . '/functions.php';
require_login();
$invoiceId = (int)($_GET['invoice'] ?? 0);
if (!$invoiceId) { http_response_code(400); echo "Falta invoice"; exit; }
$inv = get_invoice($invoiceId);
if (!$inv) { http_response_code(404); echo "Invoice no encontrada"; exit; }

$pdo = db();
$pdo->beginTransaction();
try {
  // Get or create payment
  $stmt = $pdo->prepare('SELECT * FROM payments WHERE invoice_id = ? ORDER BY created_at DESC LIMIT 1');
  $stmt->execute([$invoiceId]);
  $p = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$p) {
    $stmtIns = $pdo->prepare('INSERT INTO payments(invoice_id, provider, provider_payment_id, amount_cents, currency, method, status)
                              VALUES(?,?,?,?,?,?, "PENDING")');
    $stmtIns->execute([$invoiceId, 'MOCK', 'pay_' . uniqid(), $inv['amount_cents'], $inv['currency'], 'CARD']);
    $pid = $pdo->lastInsertId();
    $stmt = $pdo->prepare('SELECT * FROM payments WHERE id = ?'); $stmt->execute([$pid]); $p = $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Simulate gateway success ("webhook")
  $upd = $pdo->prepare('UPDATE payments SET status = "SUCCEEDED", provider_payment_id = ? WHERE id = ?');
  $upd->execute(['pay_' . uniqid(), $p['id']]);

  // Mark invoice as PAID & confirm enrollments
  mark_invoice_paid($invoiceId);
  confirm_enrollments_for_invoice($invoiceId);

  $pdo->commit();
  set_flash('ok','Pago con tarjeta simulado exitosamente. Matrícula confirmada si hubo cupo.');
  header('Location: invoice.php?id=' . $invoiceId);
  // o, si quieres ser explícito:
  // header('Location: /tarazona/matricula/invoice.php?id=' . $invoiceId);
  exit;
} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  set_flash('err','Error simulando pago: ' . $e->getMessage());
  header('Location: invoice.php?id=' . $invoiceId);
  // o:
  // header('Location: /tarazona/matricula/invoice.php?id=' . $invoiceId);
  exit;
}
