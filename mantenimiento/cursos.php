<?php
require __DIR__ . '/../db.php';
require __DIR__ . '/../functions.php';
require_role('ADMIN');

$courses = db()->query("SELECT id, code, name, credits, price_cents, image_path 
                        FROM courses ORDER BY code")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mantenimiento de cursos</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body class="landing-dashboard">
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="dashboard-main">
  <div class="container">
    <div class="card">
      <h2>Editar cursos</h2>
      <table class="dashboard-table">
        <thead>
          <tr>
            <th>Código</th>
            <th>Nombre</th>
            <th>Créditos</th>
            <th>Precio</th>
            <th>Imagen</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($courses as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['code']) ?></td>
              <td><?= htmlspecialchars($c['name']) ?></td>
              <td style="text-align:center;"><?= (int)$c['credits'] ?></td>
              <td style="text-align:center;">S/ <?= cents_to_money((int)$c['price_cents']) ?></td>
              <td>
                <?php if(!empty($c['image_path'])): ?>
                  <img src="../<?= htmlspecialchars($c['image_path']) ?>" style="width:70px;border-radius:8px;">
                <?php else: ?>
                  <span class="small">Sin imagen</span>
                <?php endif; ?>
              </td>
              <td>
                <a class="btn" href="editar_curso.php?id=<?= (int)$c['id'] ?>">Editar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <a href="../admin_dashboard.php" class="btn btn-warn" style="margin-top:12px;">Volver</a>
    </div>
  </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
