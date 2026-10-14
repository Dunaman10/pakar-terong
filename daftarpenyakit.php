<?php
// Session harus di paling atas sebelum HTML apapun
require_once __DIR__ . '/admin/sesi.php'; mulai_sesi();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Daftar Penyakit & Hama - Sistem Pakar Tanaman Terong</title>

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
      position: sticky;
      top: 0;
      z-index: 1000;
      padding: 0 2rem;
      height: 64px;
      display: flex;
      align-items: center;
      background: rgba(245, 247, 244, 0.85);
      backdrop-filter: blur(20px) saturate(1.4);
      -webkit-backdrop-filter: blur(20px) saturate(1.4);
      border-bottom: 1px solid var(--border-subtle);
      transition: box-shadow 0.3s ease;
    }
    .navbar-custom.scrolled {
      box-shadow: var(--shadow-sm);
    }
    .navbar-inner {
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .navbar-brand-custom {
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: 1.1rem;
      color: var(--primary);
      text-decoration: none;
      letter-spacing: -0.02em;
      display: flex;
      align-items: center;
      gap: 8px;
      margin-right: 1.5rem;
    }
    .brand-dot {
      width: 8px;
      height: 8px;
      background: var(--accent);
      border-radius: 50%;
      display: inline-block;
      flex-shrink: 0;
    }
    .nav-link-custom {
      font-size: 0.875rem;
      font-weight: 400;
      color: var(--text-secondary);
      text-decoration: none;
      padding: 6px 14px;
      border-radius: 6px;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap;
    }
    .nav-link-custom:hover,
    .nav-link-custom.active {
      color: var(--primary);
      background: rgba(15, 61, 46, 0.06);
    }
    .nav-link-custom i { font-size: 0.9em; opacity: 0.7; }
    .navbar-spacer { flex: 1; }
    .btn-login {
      font-size: 0.875rem;
      font-weight: 500;
      color: var(--primary);
      background: transparent;
      border: 1.5px solid var(--border);
      padding: 7px 18px;
      border-radius: 6px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s ease;
      white-space: nowrap;
    }
    .btn-login:hover {
      background: var(--primary);
      color: #fff;
      border-color: var(--primary);
    }

    /* ===== HERO ===== */
    .page-hero {
      background: var(--primary);
      background-image: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 100%);
      color: white;
      padding: 48px 0 40px;
    }
    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(192,132,252,0.2);
      border: 1px solid rgba(192,132,252,0.35);
      color: var(--accent);
      padding: 5px 14px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 14px;
    }
    .page-hero h1 {
      font-size: 1.85rem;
      font-weight: 700;
      margin-bottom: 8px;
      color: white;
    }
    .page-hero p {
      font-size: 14px;
      color: rgba(255,255,255,0.75);
      margin: 0;
    }

    /* ===== TOMBOL KEMBALI ===== */
    .btn-kembali {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: transparent;
      color: var(--primary);
      border: 1.5px solid var(--primary);
      padding: 8px 20px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.2s;
    }
    .btn-kembali:hover {
      background: var(--primary);
      color: white;
    }

    /* ===== CARD PENYAKIT ===== */
    .penyakit-card {
      background: var(--surface-card);
      border-radius: 14px;
      box-shadow: var(--shadow-md);
      margin-bottom: 20px;
      overflow: hidden;
      border: 1px solid var(--border);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .penyakit-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }
    .penyakit-card-header {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-mid) 100%);
      padding: 14px 22px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .badge-no {
      background: rgba(255,255,255,0.2);
      color: white;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      font-weight: 700;
      flex-shrink: 0;
    }
    .badge-kode {
      background: rgba(192,132,252,0.25);
      color: var(--accent);
      padding: 3px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 700;
      border: 1px solid rgba(192,132,252,0.3);
      flex-shrink: 0;
    }
    .penyakit-nama {
      color: white;
      font-size: 15px;
      font-weight: 700;
      margin: 0;
    }
    .penyakit-card-body {
      padding: 20px 22px;
    }
    .info-section {
      background: linear-gradient(135deg, rgba(192,132,252,0.05) 0%, transparent 60%);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 14px 16px;
      margin-bottom: 12px;
    }
    .info-section:last-child {
      margin-bottom: 0;
    }
    .info-label {
      font-size: 11px;
      font-weight: 700;
      color: var(--primary-mid);
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 6px;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }
    .info-label i {
      font-size: 14px;
      color: var(--accent);
    }
    .info-text {
      font-size: 13.5px;
      color: var(--text-secondary);
      text-align: justify;
      line-height: 1.75;
      margin: 0;
    }

    /* ===== FOOTER ===== */
    .footer-custom {
      text-align: center;
      padding: 18px 0;
      font-size: 13px;
      color: var(--text-muted);
      border-top: 1px solid var(--border);
      background: var(--surface-card);
      margin-top: 16px;
    }
    .footer-admin-link { color: var(--text-muted); text-decoration: none; }
    .footer-admin-link:hover { color: var(--primary-mid); }
    .navbar-user {
      font-size: 0.85rem;
      color: var(--text-secondary);
      display: flex;
      align-items: center;
      gap: 6px;
      margin-right: 0.5rem;
      white-space: nowrap;
    }
  </style>
</head>

<body>

  <!-- ===== NAVBAR — sama persis dengan index.php ===== -->
  <nav class="navbar-custom" id="navbar">
    <div class="navbar-inner">
      <a class="navbar-brand-custom" href="./index.php">
        <span class="brand-dot"></span>
        Sistem Pakar
      </a>
      <a class="nav-link-custom" href="./index.php">
        <i class="bi bi-house-fill"></i> Home
      </a>
      <a class="nav-link-custom active" href="./daftarpenyakit.php">
        <i class="bi bi-journal-text"></i> Daftar Penyakit & Hama
      </a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a class="nav-link-custom" href="./riwayat.php">
          <i class="bi bi-clock-history"></i> Riwayat Saya
        </a>
      <?php endif; ?>
      <div class="navbar-spacer"></div>
      <?php if (isset($_SESSION['user_id'])): ?>
        <span class="navbar-user">
          <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['user_nama']) ?>
        </span>
        <a class="btn-login" href="javascript:;" data-bs-toggle="modal" data-bs-target="#logoutModal">
          <i class="bi bi-box-arrow-right"></i> Logout
        </a>
      <?php else: ?>
        <a class="nav-link-custom" href="./login.php">
          <i class="bi bi-box-arrow-in-right"></i> Login
        </a>
        <a class="btn-login" href="./register.php">
          <i class="bi bi-person-plus"></i> Daftar
        </a>
      <?php endif; ?>
    </div>
  </nav>

  <!-- ===== HERO ===== -->
  <div class="page-hero">
    <div class="container">
      <?php
      include './admin/koneksi.php';
      $total = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM tb_penyakit"))[0];
      ?>
      <h1><i class="bi bi-clipboard2-pulse-fill me-2"></i>Daftar Penyakit & Hama Tanaman Terong</h1>
      <p>Informasi definisi dan solusi penanganan penyakit serta hama pada tanaman terong</p>
    </div>
  </div>

  <!-- ===== KONTEN ===== -->
  <div class="container py-4">

    <div class="mb-4">
      <a href="./index.php" class="btn-kembali">
        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
      </a>
    </div>

    <?php
    $sql = "SELECT * FROM tb_penyakit ORDER BY CAST(SUBSTRING(kdpenyakit, 2) AS UNSIGNED)";
    $qry = mysqli_query($koneksi, $sql) or die("Terjadi kesalahan pada server.");
    $no = 0;
    while ($data = mysqli_fetch_array($qry)) {
      $no++;
    ?>
      <div class="penyakit-card">
        <div class="penyakit-card-header">
          <div class="badge-no"><?php echo $no; ?></div>
          <span class="badge-kode"><?php echo htmlspecialchars($data['kdpenyakit']); ?></span>
          <h5 class="penyakit-nama"><?php echo htmlspecialchars($data['nama_penyakit']); ?></h5>
        </div>
        <div class="penyakit-card-body">
          <div class="info-section">
            <div class="info-label">
              <i class="bi bi-info-circle-fill"></i> Definisi Penyakit
            </div>
            <p class="info-text"><?php echo htmlspecialchars($data['definisi']); ?></p>
          </div>
          <div class="info-section">
            <div class="info-label">
              <i class="bi bi-lightbulb-fill"></i> Solusi &amp; Penanganan
            </div>
            <p class="info-text"><?php echo htmlspecialchars($data['solusi']); ?></p>
          </div>
        </div>
      </div>
    <?php } ?>

  </div>

  <div class="footer-custom">
    Sistem Pakar Penyakit dan Hama Tanaman Terong &mdash; Metode Dempster-Shafer
    &nbsp;·&nbsp; <a href="./admin/login.php" class="footer-admin-link">Login Admin</a>
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
  <script>
    // Scroll effect navbar — sama dengan index.php
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 10);
    });
  </script>

</body>
</html>