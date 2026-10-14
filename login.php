<?php
require_once __DIR__ . '/admin/sesi.php'; mulai_sesi();
if (isset($_SESSION['user_id'])) {
  header("Location: ./index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Login - Sistem Pakar Tanaman Terong</title>

  <link href="./assets/img/favicon.png" rel="icon">
  <link href="./assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800" rel="stylesheet">
  <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

  <style>
    :root {
      --primary:       #3b1a6b;
      --primary-mid:   #6d3bbf;
      --accent:        #c084fc;
      --accent-light:  #ede9fe;
      --surface:       #f8f6ff;
      --text-primary:  #1e0a3c;
      --text-secondary:#5b4a7a;
      --text-muted:    #9d8fbb;
      --border:        rgba(192, 132, 252, 0.2);
      --shadow-lg:     0 20px 48px rgba(59,26,107,0.16), 0 8px 16px rgba(59,26,107,0.08);
    }
    * { box-sizing: border-box; }
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(160deg, var(--primary) 0%, var(--primary-mid) 100%);
      color: var(--text-primary);
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }
    .auth-card {
      background: #fff;
      border-radius: 20px;
      box-shadow: var(--shadow-lg);
      padding: 2.5rem 2.25rem;
      width: 100%;
      max-width: 420px;
    }
    .auth-brand {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 800;
      font-size: 1.1rem;
      color: var(--primary);
      text-decoration: none;
      margin-bottom: 1.5rem;
    }
    .auth-brand .dot {
      width: 8px; height: 8px; background: var(--accent); border-radius: 50%;
    }
    .auth-title { font-weight: 700; font-size: 1.4rem; margin-bottom: 0.25rem; }
    .auth-sub { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.75rem; }
    .form-label { font-weight: 500; font-size: 0.88rem; color: var(--text-secondary); }
    .form-control {
      border-radius: 10px;
      border: 1.5px solid var(--border);
      padding: 10px 14px;
      font-size: 0.92rem;
    }
    .form-control:focus {
      border-color: var(--primary-mid);
      box-shadow: 0 0 0 3px var(--accent-light);
    }
    .btn-masuk {
      background: var(--primary);
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 500;
      width: 100%;
      transition: all 0.2s ease;
    }
    .btn-masuk:hover { background: var(--primary-mid); color: #fff; }
    .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.88rem; color: var(--text-secondary); }
    .auth-footer a { color: var(--primary-mid); font-weight: 500; text-decoration: none; }
    .guest-note {
      text-align: center; font-size: 0.82rem; color: var(--text-muted);
      margin-top: 0.75rem;
    }
    .guest-note a { color: var(--text-secondary); text-decoration: underline; }
    .alert-error {
      background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c;
      border-radius: 10px; padding: 10px 14px; font-size: 0.85rem; margin-bottom: 1.25rem;
    }
  </style>
</head>

<body>
  <div class="auth-card">
    <a href="./index.php" class="auth-brand">
      <span class="dot"></span> Sistem Pakar
    </a>
    <div class="auth-title">Masuk ke Akun</div>
    <div class="auth-sub">Login untuk melihat riwayat konsultasi kamu.</div>

    <?php if (isset($_GET['pesan'])): ?>
      <div class="alert-error">
        Email atau password salah. Silakan coba lagi.
      </div>
    <?php endif; ?>

    <form action="./proseslogin.php" method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required autofocus>
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
      </div>
      <button type="submit" class="btn-masuk">Login</button>
    </form>

    <div class="auth-footer">
      Belum punya akun? <a href="./register.php">Daftar di sini</a>
    </div>
    <div class="guest-note">
      Atau <a href="./konsultasi.php">lanjut konsultasi tanpa login</a>
    </div>
  </div>
</body>
</html>
