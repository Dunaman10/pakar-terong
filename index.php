<?php require_once __DIR__ . '/admin/sesi.php'; mulai_sesi(); ?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Sistem Pakar — Diagnosa Penyakit & Hama Tanaman Terong</title>
  <meta content="Sistem pakar untuk mendiagnosa penyakit dan hama tanaman terong menggunakan metode Dempster Shafer" name="description">

  <!-- Favicons -->
  <link href="./assets/img/favicon.png" rel="icon">
  <link href="./assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

  <!-- Vendor CSS -->
  <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root {
      --primary: #3b1a6b;
      --primary-mid: #6d3bbf;
      --accent: #c084fc;
      --accent-light: #ede9fe;
      --accent-glow: rgba(192, 132, 252, 0.25);
      --surface: #f8f6ff;
      --surface-card: #ffffff;
      --text-primary: #1e0a3c;
      --text-secondary: #5b4a7a;
      --text-muted: #9d8fbb;
      --border: rgba(192, 132, 252, 0.2);
      --border-subtle: rgba(59, 26, 107, 0.08);
      --shadow-sm: 0 1px 3px rgba(59, 26, 107, 0.06), 0 1px 2px rgba(59, 26, 107, 0.04);
      --shadow-md: 0 4px 16px rgba(59, 26, 107, 0.12), 0 2px 6px rgba(59, 26, 107, 0.06);
      --shadow-lg: 0 20px 48px rgba(59, 26, 107, 0.16), 0 8px 16px rgba(59, 26, 107, 0.08);
      --radius-sm: 8px;
      --radius-md: 14px;
      --radius-lg: 20px;
      --radius-xl: 28px;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'DM Sans', sans-serif;
      background-color: var(--surface);
      color: var(--text-primary);
      overflow-x: hidden;
      -webkit-font-smoothing: antialiased;
    }

    /* ─── BACKGROUND ─── */
    .bg-canvas {
      position: fixed;
      inset: 0;
      z-index: 0;
      pointer-events: none;
      background:
        radial-gradient(ellipse 80% 60% at 10% 0%, rgba(192,132,252,0.12) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at 90% 100%, rgba(59,26,107,0.08) 0%, transparent 55%),
        #f8f6ff;
    }
    .bg-canvas::after {
      content: '';
      position: absolute;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%233b1a6b' fill-opacity='0.025'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* ─── NAVBAR ─── */
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
      border-radius: var(--radius-sm);
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap;
    }
    .nav-link-custom:hover {
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
      border-radius: var(--radius-sm);
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
    .navbar-user {
      font-size: 0.85rem;
      color: var(--text-secondary);
      display: flex;
      align-items: center;
      gap: 6px;
      margin-right: 0.5rem;
      white-space: nowrap;
    }

    /* ─── HERO ─── */
    .hero-section {
      position: relative;
      z-index: 1;
      min-height: 620px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 140px 2rem 100px;
      text-align: center;
      overflow: hidden;
      /* GANTI url gambar di bawah ini dengan gambar kamu sendiri */
      background-image: linear-gradient(180deg, rgba(30,10,60,0.72) 0%, rgba(30,10,60,0.55) 45%, rgba(30,10,60,0.85) 100%), url('./assets/img/hero-bg.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
    .hero-inner {
      position: relative;
      z-index: 1;
      max-width: 720px;
      margin: 0 auto;
    }
    .hero-tag {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.25);
      color: #fff;
      font-size: 0.75rem;
      font-weight: 500;
      padding: 5px 12px;
      border-radius: 100px;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      margin-bottom: 1.5rem;
      animation: fadeUp 0.5s ease both;
    }
    .hero-tag-dot {
      width: 6px;
      height: 6px;
      background: var(--accent);
      border-radius: 50%;
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.6; transform: scale(0.8); }
    }
    .hero-title {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: clamp(2rem, 4vw, 3rem);
      line-height: 1.18;
      letter-spacing: -0.01em;
      color: #fff;
      margin-bottom: 1.25rem;
      animation: fadeUp 0.55s 0.07s ease both;
    }
    .hero-title .accent-text {
      color: var(--accent);
      position: relative;
    }
    .hero-desc {
      font-size: 1rem;
      font-weight: 300;
      line-height: 1.75;
      color: rgba(255, 255, 255, 0.82);
      margin: 0 auto 2.5rem;
      max-width: 520px;
      animation: fadeUp 0.55s 0.14s ease both;
    }
    .hero-actions {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1rem;
      flex-wrap: wrap;
      animation: fadeUp 0.55s 0.21s ease both;
    }
    .btn-primary-custom {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: var(--primary);
      color: #fff;
      font-size: 0.95rem;
      font-weight: 500;
      padding: 14px 28px;
      border-radius: var(--radius-md);
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.25s ease;
      box-shadow: 0 4px 14px rgba(15, 61, 46, 0.25), 0 0 0 0 rgba(62, 207, 142, 0);
      position: relative;
      overflow: hidden;
    }
    .btn-primary-custom::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, transparent 40%, rgba(62,207,142,0.15));
      opacity: 0;
      transition: opacity 0.25s ease;
    }
    .btn-primary-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(15, 61, 46, 0.32), 0 0 0 4px var(--accent-glow);
      color: #fff;
    }
    .btn-primary-custom:hover::before { opacity: 1; }
    .btn-primary-custom .btn-arrow {
      width: 22px;
      height: 22px;
      background: rgba(255,255,255,0.15);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      transition: transform 0.2s ease;
    }
    .btn-primary-custom:hover .btn-arrow { transform: translateX(3px); }

    .btn-secondary-custom {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: #fff;
      font-size: 0.9rem;
      font-weight: 400;
      padding: 14px 20px;
      border-radius: var(--radius-md);
      text-decoration: none;
      border: 1.5px solid rgba(255, 255, 255, 0.35);
      background: rgba(255, 255, 255, 0.08);
      transition: all 0.2s ease;
    }
    .btn-secondary-custom:hover {
      border-color: rgba(255, 255, 255, 0.6);
      background: rgba(255, 255, 255, 0.18);
    }

    /* ─── DIVIDER ─── */
    .divider-section {
      position: relative;
      z-index: 1;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem;
    }
    .divider-line {
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--border-subtle) 20%, var(--border-subtle) 80%, transparent);
    }

    /* ─── FEATURE CARDS ─── */
    .features-section {
      position: relative;
      z-index: 1;
      max-width: 1200px;
      margin: 0 auto;
      padding: 80px 2rem;
    }
    .features-label {
      font-size: 0.75rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--text-muted);
      margin-bottom: 0.75rem;
      animation: fadeUp 0.5s ease both;
    }
    .features-title {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: clamp(1.5rem, 2.5vw, 2rem);
      color: var(--text-primary);
      letter-spacing: -0.03em;
      margin-bottom: 3rem;
      animation: fadeUp 0.5s 0.07s ease both;
    }
    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.25rem;
    }
    .feature-card {
      background: rgba(255,255,255,0.75);
      backdrop-filter: blur(12px);
      border: 1px solid var(--border-subtle);
      border-radius: var(--radius-lg);
      padding: 2rem;
      transition: all 0.3s ease;
      cursor: default;
      animation: fadeUp 0.5s ease both;
    }
    .feature-card:hover {
      transform: translateY(-4px);
      box-shadow: var(--shadow-md);
      border-color: var(--border);
      background: rgba(255,255,255,0.95);
    }
    .feature-icon {
      width: 44px;
      height: 44px;
      background: rgba(192,132,252,0.1);
      border: 1px solid rgba(192,132,252,0.2);
      border-radius: var(--radius-sm);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      color: var(--primary-mid);
      margin-bottom: 1.25rem;
    }
    .feature-title {
      font-family: 'Poppins', sans-serif;
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
      letter-spacing: -0.01em;
    }
    .feature-desc {
      font-size: 0.85rem;
      color: var(--text-secondary);
      line-height: 1.65;
      font-weight: 300;
    }

    /* ─── CTA BAND ─── */
    .cta-section {
      position: relative;
      z-index: 1;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 2rem 100px;
    }
    .cta-band {
      background: var(--primary);
      border-radius: var(--radius-xl);
      padding: 3.5rem 3rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 2rem;
      position: relative;
      overflow: hidden;
    }
    .cta-band::before {
      content: '';
      position: absolute;
      top: -60px;
      right: -60px;
      width: 240px;
      height: 240px;
      background: radial-gradient(circle, rgba(192,132,252,0.18) 0%, transparent 70%);
      pointer-events: none;
    }
    .cta-band::after {
      content: '';
      position: absolute;
      bottom: -40px;
      left: 10%;
      width: 160px;
      height: 160px;
      background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
      pointer-events: none;
    }
    .cta-text { position: relative; z-index: 1; }
    .cta-heading {
      font-family: 'Poppins', sans-serif;
      font-size: clamp(1.3rem, 2.5vw, 1.8rem);
      font-weight: 800;
      color: #fff;
      letter-spacing: -0.03em;
      margin-bottom: 0.5rem;
    }
    .cta-sub {
      font-size: 0.9rem;
      color: rgba(255,255,255,0.6);
      font-weight: 300;
    }
    .btn-cta {
      position: relative;
      z-index: 1;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: var(--accent);
      color: var(--primary);
      font-size: 0.95rem;
      font-weight: 600;
      padding: 14px 28px;
      border-radius: var(--radius-md);
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.25s ease;
      white-space: nowrap;
      flex-shrink: 0;
      box-shadow: 0 4px 16px rgba(192, 132, 252, 0.35);
    }
    .btn-cta:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 28px rgba(192, 132, 252, 0.5);
      color: var(--primary);
      background: #d8b4fe;
    }

    /* ─── FOOTER ─── */
    .footer-custom {
      position: relative;
      z-index: 1;
      border-top: 1px solid var(--border-subtle);
      padding: 2rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .footer-text {
      font-size: 0.8rem;
      color: var(--text-muted);
    }
    .footer-admin-link {
      font-size: 0.8rem;
      color: var(--text-muted);
      text-decoration: none;
    }
    .footer-admin-link:hover { color: var(--primary-mid); }

    /* ─── ANIMATIONS ─── */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(18px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ─── RESPONSIVE ─── */
    @media (max-width: 900px) {
      .hero-section { padding: 100px 1.5rem 70px; min-height: 520px; }
      .features-grid { grid-template-columns: 1fr 1fr; }
      .cta-band { flex-direction: column; text-align: center; }
    }
    @media (max-width: 600px) {
      .navbar-custom { padding: 0 1rem; }
      .features-grid { grid-template-columns: 1fr; }
      .hero-section { padding: 90px 1rem 60px; min-height: 460px; }
      .features-section, .cta-section { padding-left: 1rem; padding-right: 1rem; }
      .cta-band { padding: 2.5rem 1.5rem; border-radius: var(--radius-lg); }
    }
  </style>
</head>

<body>

  <!-- Background -->
  <div class="bg-canvas"></div>

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

  <!-- HERO -->
  <section class="hero-section">
    <div class="hero-inner">
      <h1 class="hero-title">
        Diagnosa <span class="accent-text">Penyakit</span> Tanaman Terong Secara Cerdas
      </h1>
      <p class="hero-desc">
        Sistem pakar berbasis AI yang membantu petani dan peneliti mengidentifikasi penyakit serta hama tanaman terong secara akurat berdasarkan gejala yang diamati di lapangan.
      </p>
      <div class="hero-actions">
        <a href="./konsultasi.php" class="btn-primary-custom">
          Mulai Konsultasi
          <span class="btn-arrow"><i class="bi bi-arrow-right"></i></span>
        </a>
        <a href="./daftarpenyakit.php" class="btn-secondary-custom">
          <i class="bi bi-journal-text"></i>
          Lihat Daftar Penyakit
        </a>
      </div>
    </div>
  </section>

  <!-- Divider -->
  <div class="divider-section">
    <div class="divider-line"></div>
  </div>

  <!-- FEATURE CARDS -->
  <section class="features-section">
    <p class="features-label">Fitur Unggulan</p>
    <h2 class="features-title">Kenapa menggunakan sistem ini?</h2>

    <div class="features-grid">
      <div class="feature-card" style="animation-delay: 0.05s">
        <div class="feature-icon">
          <i class="bi bi-search"></i>
        </div>
        <h3 class="feature-title">Diagnosa Berbasis Gejala</h3>
        <p class="feature-desc">Cukup pilih gejala yang terlihat pada tanaman, sistem akan memproses dan menentukan kemungkinan penyakit secara otomatis.</p>
      </div>

      <div class="feature-card" style="animation-delay: 0.1s">
        <div class="feature-icon">
          <i class="bi bi-shield-check"></i>
        </div>
        <h3 class="feature-title">Metode Ilmiah Terpercaya</h3>
        <p class="feature-desc">Menggunakan metode Dempster Shafer yang diakui secara ilmiah untuk menangani ketidakpastian dalam proses diagnosa.</p>
      </div>

      <div class="feature-card" style="animation-delay: 0.15s">
        <div class="feature-icon">
          <i class="bi bi-lightning-charge"></i>
        </div>
        <h3 class="feature-title">Hasil Instan</h3>
        <p class="feature-desc">Dapatkan hasil diagnosa lengkap dengan persentase kepercayaan dan rekomendasi penanganan dalam hitungan detik.</p>
      </div>
    </div>
  </section>

  <!-- CTA BAND -->
  <section class="cta-section">
    <div class="cta-band">
      <div class="cta-text">
        <h3 class="cta-heading">Siap mendiagnosa tanaman terong Anda?</h3>
        <p class="cta-sub">Gratis, cepat, dan akurat. Registrasi untuk mendapatkan riwayat konsultasi.</p>
      </div>
      <a href="./konsultasi.php" class="btn-cta">
        <i class="bi bi-play-fill"></i>
        Mulai Sekarang
      </a>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="footer-custom" style="gap: 1rem;">
    <p class="footer-text" style="margin: 0;">© 2026 Sistem Pakar — Diagnosa Penyakit & Hama Tanaman Terong</p>
    <span class="footer-text">·</span>
    <a href="./admin/login.php" class="footer-admin-link">Login Admin</a>
  </footer>

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

  <!-- Vendor JS Files -->
  <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Navbar scroll effect -->
  <script>
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 10);
    });

    // Intersection Observer for animated elements
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.animationPlayState = 'running';
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.feature-card').forEach(el => {
      el.style.animationPlayState = 'paused';
      observer.observe(el);
    });
  </script>

</body>

</html>