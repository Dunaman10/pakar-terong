<?php
require_once __DIR__ . '/admin/sesi.php'; mulai_sesi();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
// Kalau sudah login, langsung lempar ke halaman utama
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
  <title>Daftar Akun - Sistem Pakar Tanaman Terong</title>

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
      max-width: 480px;
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
    .btn-daftar {
      background: var(--primary);
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 500;
      width: 100%;
      transition: all 0.2s ease;
    }
    .btn-daftar:hover { background: var(--primary-mid); color: #fff; }
    .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.88rem; color: var(--text-secondary); }
    .auth-footer a { color: var(--primary-mid); font-weight: 500; text-decoration: none; }
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
    <div class="auth-title">Buat Akun Baru</div>
    <div class="auth-sub">Daftar supaya riwayat konsultasi kamu tersimpan.</div>

    <?php if (isset($_GET['pesan'])): ?>
      <div class="alert-error">
        <?php
        $pesan_map = [
          'email_terdaftar' => 'Email ini sudah terdaftar. Silakan login.',
          'password_beda'   => 'Konfirmasi password tidak sama.',
          'gagal'           => 'Pendaftaran gagal, silakan coba lagi.',
          'kosong'          => 'Mohon lengkapi semua data yang wajib diisi.',
          'email_invalid'   => 'Format email tidak valid.',
          'password_pendek' => 'Password minimal 8 karakter.',
          'data_invalid'    => 'Data tidak valid. Periksa kembali umur, nama, alamat, dan nomor HP.',
        ];
        echo htmlspecialchars($pesan_map[$_GET['pesan']] ?? 'Terjadi kesalahan.');
        ?>
      </div>
    <?php endif; ?>

    <form action="./prosesregister.php" method="POST">
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama kamu" required
          value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
      </div>
      <div class="row g-2">
        <div class="col-6 mb-3">
          <label for="umur" class="form-label">Umur</label>
          <input type="number" class="form-control" id="umur" name="umur" placeholder="Tahun" min="1" max="120" required
            value="<?= isset($_POST['umur']) ? htmlspecialchars($_POST['umur']) : '' ?>">
        </div>
        <div class="col-6 mb-3">
          <label class="form-label">Jenis Kelamin</label>
          <div class="d-flex gap-3 pt-2">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="Laki-laki" required
                <?= (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] === 'Laki-laki') ? 'checked' : '' ?>>
              <label class="form-check-label" for="jk_l" style="font-size:0.88rem;">Laki-laki</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="Perempuan"
                <?= (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] === 'Perempuan') ? 'checked' : '' ?>>
              <label class="form-check-label" for="jk_p" style="font-size:0.88rem;">Perempuan</label>
            </div>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Alamat lengkap" required><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required
          value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
      </div>
      <div class="mb-3">
        <label for="no_hp" class="form-label">No. HP / WhatsApp <span class="text-muted">(opsional)</span></label>
        <input type="tel" class="form-control" id="no_hp" name="no_hp" placeholder="08xxxxxxxxxx"
          value="<?= isset($_POST['no_hp']) ? htmlspecialchars($_POST['no_hp']) : '' ?>">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" minlength="6" required>
      </div>
      <div class="mb-4">
        <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
        <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ulangi password" minlength="6" required>
      </div>
      <button type="submit" class="btn-daftar">Daftar</button>
    </form>

    <div class="auth-footer">
      Sudah punya akun? <a href="./login.php">Login di sini</a>
    </div>
  </div>
</body>
</html>