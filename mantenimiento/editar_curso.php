<?php
require __DIR__ . '/../db.php';
require __DIR__ . '/../functions.php';
require_role('ADMIN');

$id = (int)($_GET['id'] ?? 0);
if(!$id){ http_response_code(400); exit("ID inválido"); }

$pdo = db();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $name  = trim($_POST['name'] ?? '');
  $credits = (int)($_POST['credits'] ?? 0);
  $price  = (int)($_POST['price_cents'] ?? 0);
  $image  = trim($_POST['image_path'] ?? '');

  if($name === '' || $credits <= 0 || $price < 0){
    set_flash('err', 'Completa bien los campos.');
  } else {
    $stmt = $pdo->prepare("UPDATE courses 
                           SET name=?, credits=?, price_cents=?, image_path=? 
                           WHERE id=?");
    $stmt->execute([$name, $credits, $price, $image ?: null, $id]);
    set_flash('ok', 'Curso actualizado.');
    header("Location: cursos.php"); exit;
  }
}

$stmt = $pdo->prepare("SELECT * FROM courses WHERE id=?");
$stmt->execute([$id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$course){ http_response_code(404); exit("Curso no existe"); }

$ok = flash('ok'); $err = flash('err');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Editar curso</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body class="landing-dashboard">
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="dashboard-main">
  <div class="container">
    <div class="card">
      <h2>Editar curso <?= htmlspecialchars($course['code']) ?></h2>

      <?php if($ok): ?><div class="alert alert-ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
      <?php if($err): ?><div class="alert alert-err"><?= htmlspecialchars($err) ?></div><?php endif; ?>

      <form method="post">
        <label>Nombre</label>
        <input name="name" value="<?= htmlspecialchars($course['name']) ?>" required>

        <label>Créditos</label>
        <input type="number" name="credits" min="1" value="<?= (int)$course['credits'] ?>" required>

        <label>Precio (en centavos)</label>
        <input type="number" name="price_cents" min="0" value="<?= (int)$course['price_cents'] ?>" required>
        <small class="small">Ej: S/ 45.00 = 4500</small>

        <label>Ruta de imagen (opcional)</label>
        <input name="image_path" value="<?= htmlspecialchars($course['image_path'] ?? '') ?>">
        <small class="small">Ej: img/course-1.jpg</small>

        <div style="margin-top:12px;">
          <button class="btn btn-ok" type="submit">Guardar</button>
          <a class="btn btn-warn" href="cursos.php">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
