<?php
require __DIR__ . '/db.php';

try {
  $pdo = db();
  $pdo->beginTransaction();

  // Create tables
  $pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      email TEXT UNIQUE NOT NULL,
      password_hash TEXT NOT NULL,
      role TEXT NOT NULL CHECK(role IN ('STUDENT','ADMIN')),
      created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS students (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      user_id INTEGER UNIQUE NOT NULL REFERENCES users(id) ON DELETE CASCADE,
      full_name TEXT NOT NULL,
      doc_id TEXT
    );

    CREATE TABLE IF NOT EXISTS courses (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      code TEXT UNIQUE NOT NULL,
      name TEXT NOT NULL,
      credits INTEGER NOT NULL
    );

    CREATE TABLE IF NOT EXISTS sections (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      course_id INTEGER NOT NULL REFERENCES courses(id) ON DELETE CASCADE,
      code TEXT NOT NULL,
      weekday INTEGER NOT NULL,
      start_time TEXT NOT NULL,
      end_time TEXT NOT NULL,
      capacity INTEGER NOT NULL DEFAULT 30,
      enrolled_count INTEGER NOT NULL DEFAULT 0,
      UNIQUE(course_id, code)
    );

    CREATE TABLE IF NOT EXISTS enrollments (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      student_id INTEGER NOT NULL REFERENCES students(id) ON DELETE CASCADE,
      section_id INTEGER NOT NULL REFERENCES sections(id) ON DELETE CASCADE,
      status TEXT NOT NULL CHECK(status IN ('PENDING_PAYMENT','CONFIRMED','CANCELLED')) DEFAULT 'PENDING_PAYMENT',
      invoice_id INTEGER REFERENCES invoices(id),
      created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
      UNIQUE(student_id, section_id)
    );

    CREATE TABLE IF NOT EXISTS invoices (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      student_id INTEGER NOT NULL REFERENCES students(id) ON DELETE CASCADE,
      number TEXT UNIQUE NOT NULL,
      currency TEXT NOT NULL DEFAULT 'USD',
      amount_cents INTEGER NOT NULL,
      status TEXT NOT NULL CHECK(status IN ('OPEN','PAID','VOID')) DEFAULT 'OPEN',
      created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS invoice_items (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      invoice_id INTEGER NOT NULL REFERENCES invoices(id) ON DELETE CASCADE,
      description TEXT NOT NULL,
      qty INTEGER NOT NULL DEFAULT 1,
      unit_price_cents INTEGER NOT NULL
    );

    CREATE TABLE IF NOT EXISTS payments (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      invoice_id INTEGER NOT NULL REFERENCES invoices(id) ON DELETE CASCADE,
      provider TEXT NOT NULL,                 -- 'CARD','TRANSFER','MOCK'
      provider_payment_id TEXT,
      amount_cents INTEGER NOT NULL,
      currency TEXT NOT NULL,
      method TEXT NOT NULL,                   -- 'CARD','TRANSFER'
      status TEXT NOT NULL CHECK(status IN ('PENDING','SUCCEEDED','FAILED','REVIEW','REFUNDED')) DEFAULT 'PENDING',
      verified_by INTEGER REFERENCES users(id),
      verified_at TEXT,
      created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS documents (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      payment_id INTEGER REFERENCES payments(id) ON DELETE CASCADE,
      uploader_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
      filename TEXT NOT NULL,
      mime_type TEXT NOT NULL,
      path TEXT NOT NULL,
      created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    );
  ");

  // Seed admin user
  $adminEmail = 'admin@example.com';
  $exists = $pdo->prepare('SELECT id FROM users WHERE email = ?');
  $exists->execute([$adminEmail]);
  if (!$exists->fetchColumn()) {
    $stmt = $pdo->prepare('INSERT INTO users(email,password_hash,role) VALUES(?,?,?)');
    $stmt->execute([$adminEmail, password_hash('admin123', PASSWORD_DEFAULT), 'ADMIN']);
  }

  // Seed some courses & sections
  $pdo->exec("
    INSERT OR IGNORE INTO courses(id, code, name, credits) VALUES
      (1,'MAT101','Cálculo I',4),
      (2,'INF110','Programación I',3),
      (3,'ADM201','Contabilidad',3);

    INSERT OR IGNORE INTO sections(id, course_id, code, weekday, start_time, end_time, capacity) VALUES
      (1,1,'A1',1,'08:00','10:00',2),
      (2,1,'B1',3,'10:00','12:00',2),
      (3,2,'A1',2,'09:00','11:00',2),
      (4,3,'A1',4,'14:00','16:00',2);
  ");

  $pdo->commit();
  echo "<link rel='stylesheet' href='/styles.css'><div class='container'><div class='card'><h2>Base de datos inicializada</h2><p>Admin: <code>admin@example.com / admin123</code></p><p><a class='btn' href='/index.php'>Ir al inicio</a></p></div></div>";
} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  http_response_code(500);
  echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
}
