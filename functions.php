<?php
require_once __DIR__ . '/db.php';

/**
 * Devuelve el id del student según el user_id
 */
function get_student_id(int $userId) {
  $stmt = db()->prepare('SELECT id FROM students WHERE user_id = ?');
  $stmt->execute([$userId]);
  return (int)$stmt->fetchColumn();
}

/**
 * Lista todas las secciones disponibles con datos del curso
 * (incluye price_cents para mostrar precio en dashboard).
 */
function list_sections() {
  $sql = "SELECT 
            s.id as section_id, 
            c.code as course_code, 
            c.name as course_name, 
            c.credits,
            c.price_cents,
            s.code as section_code, 
            s.weekday, 
            s.start_time, 
            s.end_time, 
            s.capacity, 
            s.enrolled_count
          FROM sections s
          JOIN courses c ON c.id = s.course_id
          ORDER BY c.code, s.code";
  return db()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Añade una matrícula pendiente evitando duplicados
 */
function add_enrollment($studentId, $sectionId) {
  $stmt = db()->prepare(
    'INSERT IGNORE INTO enrollments(student_id, section_id, status)
     VALUES(?,?, "PENDING_PAYMENT")'
  );
  $stmt->execute([$studentId, $sectionId]);
}

/**
 * Secciones pendientes de facturar para un estudiante.
 * Incluye price_cents del curso.
 */
function student_pending_enrollments($studentId) {
  $stmt = db()->prepare('
      SELECT 
        e.id, 
        e.section_id, 
        c.code as course_code, 
        c.name as course_name, 
        c.credits, 
        c.price_cents,
        s.code as section_code
      FROM enrollments e
      JOIN sections s ON s.id = e.section_id
      JOIN courses c ON c.id = s.course_id
      WHERE e.student_id = ? 
        AND e.status = "PENDING_PAYMENT" 
        AND (e.invoice_id IS NULL)
  ');
  $stmt->execute([$studentId]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Facturas del estudiante
 */
function student_invoices($studentId) {
  $stmt = db()->prepare('SELECT * FROM invoices WHERE student_id = ? ORDER BY created_at DESC');
  $stmt->execute([$studentId]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Nombre corto del día
 */
function weekday_name($n) {
  $names = [1=>'Lun',2=>'Mar',3=>'Mié',4=>'Jue',5=>'Vie',6=>'Sáb',7=>'Dom'];
  return $names[$n] ?? $n;
}

/**
 * Genera una factura usando price_cents por curso.
 */
function generate_invoice($studentId) {
  $pdo   = db();
  $items = student_pending_enrollments($studentId);
  if (!$items) { return null; }

  // Total usando price_cents
  $total_cents = 0;
  foreach ($items as $it) {
    $price = isset($it['price_cents']) && $it['price_cents'] !== null
           ? (int)$it['price_cents']
           : (int)$it['credits'] * 10000; // respaldo
    $total_cents += $price;
  }

  $pdo->beginTransaction();
  try {
    $number = 'INV-' . date('Ymd-His') . '-' . random_int(1000,9999);

    $stmt = $pdo->prepare('INSERT INTO invoices(student_id, number, currency, amount_cents, status)
                           VALUES(?,?,?,?, "OPEN")');
    $stmt->execute([$studentId, $number, 'S/', $total_cents]);
    $invoiceId = (int)$pdo->lastInsertId();

    $stmtItem = $pdo->prepare('INSERT INTO invoice_items(invoice_id, description, qty, unit_price_cents)
                               VALUES(?,?,?,?)');

    foreach ($items as $it) {
      $price = isset($it['price_cents']) && $it['price_cents'] !== null
             ? (int)$it['price_cents']
             : (int)$it['credits'] * 10000;

      $desc = $it['course_code'] . ' ' . $it['course_name'] .
              ' - Sec ' . $it['section_code'] .
              ' (' . $it['credits'] . ' créditos)';

      $stmtItem->execute([$invoiceId, $desc, 1, $price]);
    }

    // Vincular enrollments a la factura
    $stmtLink = $pdo->prepare('UPDATE enrollments SET invoice_id = ? WHERE id = ?');
    foreach ($items as $it) {
      $stmtLink->execute([$invoiceId, $it['id']]);
    }

    // Crear pago simulado por tarjeta (PENDING)
    $stmtPay = $pdo->prepare('INSERT INTO payments(invoice_id, provider, provider_payment_id, amount_cents, currency, method, status)
                              VALUES(?,?,?,?,?,?, "PENDING")');
    $stmtPay->execute([$invoiceId, 'MOCK', null, $total_cents, 'S/', 'CARD']);

    $pdo->commit();
    return $invoiceId;
  } catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    throw $e;
  }
}

/**
 * Obtener factura (opcionalmente filtrada por student)
 */
function get_invoice($invoiceId, $studentId = null) {
  if ($studentId) {
    $stmt = db()->prepare('SELECT * FROM invoices WHERE id = ? AND student_id = ?');
    $stmt->execute([$invoiceId, $studentId]);
  } else {
    $stmt = db()->prepare('SELECT * FROM invoices WHERE id = ?');
    $stmt->execute([$invoiceId]);
  }
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Items de factura
 */
function invoice_items($invoiceId) {
  $stmt = db()->prepare('SELECT * FROM invoice_items WHERE invoice_id = ?');
  $stmt->execute([$invoiceId]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Último pago asociado a factura
 */
function get_payment_by_invoice($invoiceId) {
  $stmt = db()->prepare('SELECT * FROM payments WHERE invoice_id = ? ORDER BY created_at DESC LIMIT 1');
  $stmt->execute([$invoiceId]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Asegura que exista un pago por transferencia en REVIEW
 */
function ensure_transfer_payment($invoiceId) {
  $p = get_payment_by_invoice($invoiceId);
  if ($p && $p['method'] === 'TRANSFER') { return $p; }

  $inv = get_invoice($invoiceId);

  $stmt = db()->prepare('INSERT INTO payments(invoice_id, provider, provider_payment_id, amount_cents, currency, method, status)
                        VALUES(?,?,?,?,?,?, "REVIEW")');
  $stmt->execute([$invoiceId, 'TRANSFER', null, $inv['amount_cents'], $inv['currency'], 'TRANSFER']);

  $id = db()->lastInsertId();
  return get_payment($id);
}

/**
 * Obtener pago por id
 */
function get_payment($paymentId) {
  $stmt = db()->prepare('SELECT * FROM payments WHERE id = ?');
  $stmt->execute([$paymentId]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Confirmar matrículas luego de pago aprobado.
 * Actualiza cupos y estados.
 */
function confirm_enrollments_for_invoice($invoiceId) {
  $pdo = db();
  $pdo->beginTransaction();
  try {
    $enrs = $pdo->prepare('SELECT e.id, e.section_id, e.status, s.capacity, s.enrolled_count
                           FROM enrollments e
                           JOIN sections s ON s.id = e.section_id
                           WHERE e.invoice_id = ? AND e.status = "PENDING_PAYMENT"
                           ORDER BY e.id');
    $enrs->execute([$invoiceId]);
    $rows = $enrs->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $r) {
      if ($r['enrolled_count'] < $r['capacity']) {
        $upSec = $pdo->prepare('UPDATE sections 
                                SET enrolled_count = enrolled_count + 1 
                                WHERE id = ? AND enrolled_count < capacity');
        $upSec->execute([$r['section_id']]);

        if ($upSec->rowCount() === 1) {
          $upE = $pdo->prepare('UPDATE enrollments SET status = "CONFIRMED" WHERE id = ?');
          $upE->execute([$r['id']]);
        } else {
          $pdo->prepare('UPDATE enrollments SET status = "CANCELLED" WHERE id = ?')
              ->execute([$r['id']]);
        }
      } else {
        $pdo->prepare('UPDATE enrollments SET status = "CANCELLED" WHERE id = ?')
            ->execute([$r['id']]);
      }
    }

    $pdo->commit();
  } catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    throw $e;
  }
}

/**
 * Marcar factura pagada
 */
function mark_invoice_paid($invoiceId) {
  $stmt = db()->prepare('UPDATE invoices SET status = "PAID" WHERE id = ?');
  $stmt->execute([$invoiceId]);
}
?>
