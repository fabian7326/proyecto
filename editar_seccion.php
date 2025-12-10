<?php
require __DIR__ . '/db.php';
require __DIR__ . '/functions.php';

require_role('ADMIN'); // solo admins

$pdo = db();
$sectionId = (int)($_GET['id'] ?? 0);

if (!$sectionId) {
  http_response_code(400);
  echo "ID de sección inválido";
  exit;
}

// Si envían el formulario (POST) -> actualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $capacity   = (int)($_POST['capacity'] ?? 0);
  $weekday    = (int)($_POST['weekday'] ?? 0);
  $start_time = trim($_POST['start_time'] ?? '');
  $end_time   = trim($_POST['end_time'] ?? '');

  if ($capacity <= 0 || !$weekday || !$start_time || !$end_time) {
    set_flash('err', 'Completa todos los campos correctamente.');
    header('Location: editar_seccion.php?id=' . $sectionId);
    exit;
  }

  $stmt = $pdo->prepare('UPDATE sections
                         SET capacity = ?, weekday = ?, start_time = ?, end_time = ?
                         WHERE id = ?');
  $stmt->execute([$capacity, $weekday, $start_time, $end_time, $sectionId]);

  set_flash('ok', 'Sección actualizada correctamente.');
  header('Location: admin_dashboard.php');
  exit;
}

// Si es GET -> mostrar datos actuales
$stmt = $pdo->prepare('SELECT s.*, c.code AS course_code, c.name AS course_name
                       FROM sections s
                       JOIN courses c ON c.id = s.course_id
                       WHERE s.id = ?');
$stmt->execute([$sectionId]);
$section = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$section) {
  http_response_code(404);
  echo "Sección no encontrada";
  exit;
}

$ok  = flash('ok');
$err = flash('err');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <!-- Favicon -->
  <link href="img/logo.png" rel="icon">

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Editar sección</title>
  <link rel="stylesheet" href="styles.css">
</head>

<!-- ✅ MISMO ESTILO LANDING -->
<body class="landing-dashboard">

<?php include __DIR__ . '/partials/header.php'; ?>

<!-- ✅ MISMO WRAPPER -->
<main class="dashboard-main">
  <div class="container">

    <div class="card">
      <h2>Editar sección</h2>

      <p class="small" style="margin-top:6px;">
        <strong>Curso:</strong>
        <?= htmlspecialchars($section['course_code'] . ' - ' . $section['course_name']) ?>
        <br>
        <strong>Sección:</strong> <?= htmlspecialchars($section['code']) ?>
      </p>

      <?php if ($ok): ?>
        <div class="alert alert-ok"><?= htmlspecialchars($ok) ?></div>
      <?php endif; ?>
      <?php if ($err): ?>
        <div class="alert alert-err"><?= htmlspecialchars($err) ?></div>
      <?php endif; ?>

      <form method="post">

        <div class="row">
          <div class="col">
            <label>Día de la semana</label>
            <select name="weekday" required>
              <?php
                $dias = [
                  1=>'Lunes',2=>'Martes',3=>'Miércoles',4=>'Jueves',
                  5=>'Viernes',6=>'Sábado',7=>'Domingo'
                ];
                foreach ($dias as $num => $nombre):
              ?>
                <option value="<?= $num ?>" <?= $num == (int)$section['weekday'] ? 'selected' : '' ?>>
                  <?= $nombre ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col">
            <label>Hora inicio</label>
            <input type="time" name="start_time"
                   value="<?= htmlspecialchars($section['start_time']) ?>" required>
          </div>

          <div class="col">
            <label>Hora fin</label>
            <input type="time" name="end_time"
                   value="<?= htmlspecialchars($section['end_time']) ?>" required>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <label>Cupos (capacidad)</label>
            <input type="number" name="capacity" min="1"
                   value="<?= (int)$section['capacity'] ?>" required>
          </div>
        </div>

        <div style="margin-top:12px;">
          <button class="btn btn-ok" type="submit">Guardar cambios</button>
          <a class="btn" href="admin_dashboard.php">Cancelar</a>
        </div>
      </form>

    </div>

  </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
