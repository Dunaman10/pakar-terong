<?php
require_once __DIR__ . '/admin/sesi.php'; mulai_sesi();
include './admin/koneksi.php';

// Hanya bisa diakses kalau sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: ./login.php");
  exit;
}

$user_id = (int) $_SESSION['user_id'];
// Gejala diambil dari tb_detail_diagnosa (bukan lagi dari gejala_dipilih),
// digabung jadi teks lagi cuma buat ditampilkan — datanya sendiri sekarang
// atomik/1NF di tabel detail.
$sql = "SELECT h.*,
               (SELECT GROUP_CONCAT(g.gejala ORDER BY g.id SEPARATOR '; ')
                FROM tb_detail_diagnosa d
                JOIN tb_gejala g ON g.id = d.id_gejala
                WHERE d.id_history = h.id) AS gejala_list
        FROM tb_history_diagnosa h
        WHERE h.id_user = $user_id
        ORDER BY h.tanggal DESC";
$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Riwayat Konsultasi Saya - Sistem Pakar Tanaman Terong</title>

  <link href="./assets/img/favicon.png" rel="icon">
  <link href="./assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Nunito:300,400,600,700|Poppins:300,400,500,600,700,800" rel="stylesheet">
  <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

  <style>
    :root {
      --primary:       #3b1a6b;
      --primary-mid:   #6d3bbf;
      --accent:        #c084fc;
      --accent-light:  #ede9fe;
      --accent-glow:   rgba(192, 132, 252, 0.25);
      --surface:       #f8f6ff;
      --surface-card:  #ffffff;
      --text-primary:  #1e0a3c;
      --text-secondary:#5b4a7a;
      --text-muted:    #9d8fbb;
      --border:        rgba(192, 132, 252, 0.2);
      --border-subtle: rgba(59, 26, 107, 0.08);
      --shadow-sm:     0 1px 3px rgba(59,26,107,0.06), 0 1px 2px rgba(59,26,107,0.04);
      --shadow-md:     0 4px 16px rgba(59,26,107,0.12), 0 2px 6px rgba(59,26,107,0.06);
      --shadow-lg:     0 20px 48px rgba(59,26,107,0.16), 0 8px 16px rgba(59,26,107,0.08);
    }
    * { box-sizing: border-box; }
    body {
      background-color: var(--surface);
      color: var(--text-primary);
      font-family: "Poppins", sans-serif;
      margin: 0;
    }

    /* ===== NAVBAR — sama persis dengan index.php ===== */
    .navbar-custom {
      position: sticky; top: 0; z-index: 1000; padding: 0 2rem; height: 64px;
      display: flex; align-items: center;
      background: rgba(245, 247, 244, 0.85);
      backdrop-filter: blur(20px) saturate(1.4);
      -webkit-backdrop-filter: blur(20px) saturate(1.4);
      border-bottom: 1px solid var(--border-subtle);
    }
    .navbar-inner { max-width: 1200px; width: 100%; margin: 0 auto; display: flex; align-items: center; gap: 0.5rem; }
    .navbar-brand-custom {
      font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 1.1rem;
      color: var(--primary); text-decoration: none; letter-spacing: -0.02em;
      display: flex; align-items: center; gap: 8px; margin-right: 1.5rem;
    }
    .brand-dot { width: 8px; height: 8px; background: var(--accent); border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .nav-link-custom {
      font-size: 0.875rem; font-weight: 400; color: var(--text-secondary); text-decoration: none;
      padding: 6px 14px; border-radius: 6px; transition: all 0.2s ease;
      display: flex; align-items: center; gap: 6px; white-space: nowrap;
    }
    .nav-link-custom:hover, .nav-link-custom.active { color: var(--primary); background: rgba(15, 61, 46, 0.06); }
    .nav-link-custom i { font-size: 0.9em; opacity: 0.7; }
    .navbar-spacer { flex: 1; }
    .navbar-user { font-size: 0.85rem; color: var(--text-secondary); display: flex; align-items: center; gap: 6px; margin-right: 0.5rem; }
    .btn-login {
      font-size: 0.875rem; font-weight: 500; color: var(--primary); background: transparent;
      border: 1.5px solid var(--border); padding: 7px 18px; border-radius: 6px; text-decoration: none;
      display: flex; align-items: center; gap: 6px; transition: all 0.2s ease; white-space: nowrap;
    }
    .btn-login:hover { background: var(--primary); color: #fff; border-color: var(--primary); }

    /* ===== PAGE HEADER ===== */
    .page-header-band {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 100%);
      padding: 3rem 2rem;
    }
    .page-header-inner { max-width: 1000px; margin: 0 auto; color: #fff; }
    .page-header-inner h1 { font-weight: 700; font-size: 1.9rem; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 12px; }
    .page-header-inner p { color: rgba(255,255,255,0.75); margin: 0; font-size: 0.95rem; }

    .content-wrap { max-width: 1000px; margin: 2rem auto 4rem; padding: 0 2rem; }
    .btn-kembali {
      display: inline-flex; align-items: center; gap: 8px; padding: 9px 20px; border-radius: 10px;
      border: 1.5px solid var(--border); background: #fff; color: var(--text-secondary);
      font-size: 0.87rem; font-weight: 500; text-decoration: none; margin-bottom: 1.5rem;
      transition: all 0.15s;
    }
    .btn-kembali:hover { background: var(--primary); color: #fff; border-color: var(--primary); }

    .riwayat-card {
      background: var(--surface-card); border: 1px solid var(--border-subtle);
      border-radius: 16px; padding: 1.5rem; margin-bottom: 1rem; box-shadow: var(--shadow-sm);
    }
    .riwayat-top { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px; margin-bottom: 0.75rem; }
    .riwayat-tanggal { font-size: 0.78rem; color: var(--text-muted); }
    .riwayat-hasil { font-size: 1.05rem; font-weight: 700; color: var(--primary); }
    .riwayat-cf {
      background: var(--accent-light); color: var(--primary-mid); font-size: 0.78rem; font-weight: 600;
      padding: 4px 12px; border-radius: 100px; white-space: nowrap;
    }
    .riwayat-gejala { font-size: 0.85rem; color: var(--text-secondary); line-height: 1.6; }
    .riwayat-gejala .lbl { font-weight: 500; color: var(--text-primary); }

    .empty-state {
      text-align: center; padding: 4rem 2rem; background: #fff; border-radius: 16px;
      border: 1px dashed var(--border);
    }
    .empty-state i { font-size: 2.5rem; color: var(--text-muted); margin-bottom: 1rem; display: block; }
    .empty-state p { color: var(--text-secondary); margin-bottom: 1.5rem; }
    .btn-mulai {
      display: inline-flex; align-items: center; gap: 8px; background: var(--primary); color: #fff;
      padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 500; font-size: 0.9rem;
    }
    .btn-mulai:hover { background: var(--primary-mid); color: #fff; }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar-custom" id="navbar">
    <div class="navbar-inner">
      <a class="navbar-brand-custom" href="./index.php">
        <span class="brand-dot"></span>
        Sistem Pakar
      </a>
      <a class="nav-link-custom" href="./index.php">
        <i class="bi bi-house-fill"></i> Home
      </a>
      <a class="nav-link-custom" href="./daftarpenyakit.php">
        <i class="bi bi-journal-text"></i> Daftar Penyakit & Hama
      </a>
      <a class="nav-link-custom active" href="./riwayat.php">
        <i class="bi bi-clock-history"></i> Riwayat Saya
      </a>
      <div class="navbar-spacer"></div>
      <span class="navbar-user">
        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user_nama']) ?>
      </span>
      <a class="btn-login" href="javascript:;" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    </div>
  </nav>

  <!-- HEADER -->
  <div class="page-header-band">
    <div class="page-header-inner">
      <h1><i class="bi bi-clock-history"></i> Riwayat Konsultasi Saya</h1>
      <p>Semua hasil diagnosa dari konsultasi yang pernah kamu lakukan.</p>
    </div>
  </div>

  <div class="content-wrap">
    <a href="./index.php" class="btn-kembali">
      <i class="bi bi-arrow-left"></i> Kembali ke Beranda
    </a>

    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="riwayat-card">
          <div class="riwayat-top">
            <div>
              <div class="riwayat-hasil"><?= htmlspecialchars($row['hasil_diagnosa']) ?></div>
              <div class="riwayat-tanggal">
                <i class="bi bi-calendar3"></i> <?= date('d M Y, H:i', strtotime($row['tanggal'])) ?> WIB
              </div>
            </div>
            <span class="riwayat-cf"><?= number_format($row['nilai_cf'], 2) ?>% Keyakinan</span>
          </div>
          <div class="riwayat-gejala">
            <span class="lbl">Gejala:</span> <?= htmlspecialchars($row['gejala_list'] ?? '-') ?>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>Kamu belum pernah melakukan konsultasi saat login.<br>Riwayat konsultasi akan muncul di sini setelah kamu diagnosa.</p>
        <a href="./konsultasi.php" class="btn-mulai">
          <i class="bi bi-play-fill"></i> Mulai Konsultasi
        </a>
      </div>
    <?php endif; ?>
  </div>

  <!-- Logout Confirmation Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Apakah Anda yakin ingin keluar?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-footer">
          <a href="./logout.php" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Ya</a>
          <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i> Tidak</button>
        </div>
      </div>
    </div>
  </div><!-- End Logout Modal -->

  <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>