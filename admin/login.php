<?php
require_once __DIR__ . '/sesi.php'; mulai_sesi();
include "./koneksi.php";

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (isset($_SESSION['status']) && $_SESSION['status'] === 'login') {
  header("Location: ./index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login Admin - Sistem Pakar Tanaman Terong</title>

  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
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
    .admin-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--accent-light);
      color: var(--primary-mid);
      font-size: 0.72rem;
      font-weight: 600;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      padding: 4px 12px;
      border-radius: 100px;
      margin-bottom: 0.75rem;
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
    .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.88rem; }
    .auth-footer a {
      color: var(--text-secondary);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .auth-footer a:hover { color: var(--primary-mid); }
    .alert-error {
      background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c;
      border-radius: 10px; padding: 10px 14px; font-size: 0.85rem; margin-bottom: 1.25rem;
    }
  </style>
</head>

<body>
  <div class="auth-card">
    <a href="../index.php" class="auth-brand">
      <span class="dot"></span> Sistem Pakar
    </a>
    <div class="admin-badge"><i class="bi bi-shield-lock-fill"></i> Admin Panel</div>
    <div class="auth-title">Login Admin</div>
    <div class="auth-sub">Masuk untuk mengelola data gejala, penyakit, dan bobot diagnosa.</div>

    <?php if (isset($_GET['pesan'])): ?>
      <div class="alert-error">
        Maaf, username atau password salah.
      </div>
    <?php endif; ?>

    <form action="./loginkoneksi.php" method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required autofocus>
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
      </div>
      <button type="submit" class="btn-masuk">Login</button>
    </form>

    <div class="auth-footer">
      <a href="../index.php"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
    </div>
  </div>
</body>
</html>