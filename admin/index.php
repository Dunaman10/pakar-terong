<?php include './auth.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin - Sistem Pakar Penyakit dan Hama Tanaman Terong</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/favicon.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet"> -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <!-- <h4>Dashboard - Admin</h4> -->
    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <span class="d-none d-lg-block">Sistem Pakar</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <!-- End Logo -->
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="index.php">
          <i class="bi bi-house"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link" href="./penyakit/penyakit.php">
          <i class="bi bi-database"></i>
          <span>Data Penyakit</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="./gejala/gejala.php">
          <i class="bi bi-clipboard2-pulse"></i>
          <span>Data Gejala</span>
        </a>
      </li><!-- End Components Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="./relasi/relasi.php">
          <i class="bi bi-diagram-3" style="color:#0d6efd;"></i>
          <span>Relasi Gejala</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="./bobot/bobot.php">
         <i class="bi bi-sliders" style="color:#0d6efd;"></i>
         <span>Bobot / Belief</span>
        </a>
      </li><!-- End Profile Page Nav -->

      <li class="nav-item">
        <a class="nav-link" href="./laporan/lapgejala.php">
          <i class="bi bi-journal-text"></i><span>Laporan</span>
        </a>
      </li><!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="javascript:;" data-bs-toggle="modal" data-bs-target="#logoutmodal">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Logout</span>
        </a>
      </li><!-- End Login Page Nav -->
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav style="--bs-breadcrumb-divider: '>';">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Admin</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="dashboard">

      <!-- Stat Cards Row -->
      <div class="row g-3 mb-4">

        <!-- Daftar Penyakit -->
        <div class="col-xxl-3 col-md-6">
          <a href="./penyakit/penyakit.php" class="text-decoration-none">
            <div class="card info-card sales-card h-100">
              <div class="card-body d-flex align-items-center">
                <div class="ps-3 flex-grow-1">
                  <h6 class="card-subtitle mb-1 text-muted">Daftar Penyakit</h6>
                  <span class="card-title fs-1 fw-bold text-dark">
                    <?php
                      $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_penyakit");
                      $r = mysqli_fetch_assoc($q);
                      echo $r['total'] ?? '0';
                    ?>
                  </span>
                  <p class="text-muted small mb-0 mt-1">Total data penyakit</p>
                </div>
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background:#e8f5e9;">
                  <i class="bi bi-database" style="color:#198754;font-size:1.5rem;"></i>
                </div>
              </div>
            </div>
          </a>
        </div>

        <!-- Daftar Gejala -->
        <div class="col-xxl-3 col-md-6">
          <a href="./gejala/gejala.php" class="text-decoration-none">
            <div class="card info-card revenue-card h-100">
              <div class="card-body d-flex align-items-center">
                <div class="ps-3 flex-grow-1">
                  <h6 class="card-subtitle mb-1 text-muted">Daftar Gejala</h6>
                  <span class="card-title fs-1 fw-bold text-dark">
                    <?php
                      $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_gejala");
                      $r = mysqli_fetch_assoc($q);
                      echo $r['total'] ?? '0';
                    ?>
                  </span>
                  <p class="text-muted small mb-0 mt-1">Total data gejala</p>
                </div>
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background:#e3f2fd;">
                  <i class="bi bi-clipboard2-pulse" style="color:#0d6efd;font-size:1.5rem;"></i>
                </div>
              </div>
            </div>
          </a>
        </div>

        <!-- Daftar Relasi -->
        <div class="col-xxl-3 col-md-6">
          <a href="./relasi/relasi.php" class="text-decoration-none">
            <div class="card info-card h-100">
              <div class="card-body d-flex align-items-center">
                <div class="ps-3 flex-grow-1">
                  <h6 class="card-subtitle mb-1 text-muted">Relasi Gejala</h6>
                  <span class="card-title fs-1 fw-bold text-dark">
                    <?php
                      $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_relasi");
                      $r = mysqli_fetch_assoc($q);
                      echo $r['total'] ?? '0';
                    ?>
                  </span>
                  <p class="text-muted small mb-0 mt-1">Total relasi gejala-penyakit</p>
                </div>
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background:#fff3e0;">
                  <i class="bi bi-diagram-3" style="color:#fd7e14;font-size:1.5rem;"></i>
                </div>
              </div>
            </div>
          </a>
        </div>

        <!-- Daftar Bobot -->
        <div class="col-xxl-3 col-md-6">
          <a href="./bobot/bobot.php" class="text-decoration-none">
            <div class="card info-card h-100">
              <div class="card-body d-flex align-items-center">
                <div class="ps-3 flex-grow-1">
                  <h6 class="card-subtitle mb-1 text-muted">Bobot / Belief</h6>
                  <span class="card-title fs-1 fw-bold text-dark">
                    <?php
                      $q = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_bobot");
                      $r = mysqli_fetch_assoc($q);
                      echo $r['total'] ?? '0';
                    ?>
                  </span>
                  <p class="text-muted small mb-0 mt-1">Total data bobot</p>
                </div>
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background:#fce4ec;">
                  <i class="bi bi-sliders" style="color:#d63384;font-size:1.5rem;"></i>
                </div>
              </div>
            </div>
          </a>
        </div>

      </div><!-- End Stat Cards Row -->

      <!-- Tentang Sistem - Full Width / Landscape -->
      <div class="row g-3">
        <div class="col-12">
          <div class="card">
            <div class="card-body pt-4">
              <h5 class="card-title">Tentang Sistem</h5>
              <p class="text-muted small mb-3">
                Sistem Pakar penyakit dan hama tanaman Terong menggunakan metode
                <strong>Dempster-Shafer</strong> untuk mendiagnosa jenis penyakit dan hama
                berdasarkan gejala yang muncul pada tanaman.
              </p>
              <hr>
              <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-0">
                <div class="col">
                  <div class="d-flex align-items-center py-2 pe-3">
                    <i class="bi bi-database-fill me-2" style="color:#198754;"></i>
                    <span class="small">Manajemen Data Penyakit</span>
                  </div>
                </div>
                <div class="col">
                  <div class="d-flex align-items-center py-2 pe-3">
                    <i class="bi bi-clipboard2-pulse-fill me-2" style="color:#0d6efd;"></i>
                    <span class="small">Manajemen Data Gejala</span>
                  </div>
                </div>
                <div class="col">
                  <div class="d-flex align-items-center py-2 pe-3">
                    <i class="bi bi-diagram-3-fill me-2" style="color:#fd7e14;"></i>
                    <span class="small">Relasi Gejala &amp; Penyakit</span>
                  </div>
                </div>
                <div class="col">
                  <div class="d-flex align-items-center py-2 pe-3">
                    <i class="bi bi-sliders me-2" style="color:#d63384;"></i>
                    <span class="small">Pengaturan Bobot / Belief</span>
                  </div>
                </div>
                <div class="col">
                  <div class="d-flex align-items-center py-2 pe-3">
                    <i class="bi bi-journal-text me-2" style="color:#6f42c1;"></i>
                    <span class="small">Laporan Hasil Diagnosa</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!-- End Bottom Row -->

    </section>

  </main><!-- End #main -->

  <!-- Logout Modal -->
  <div class="modal fade" id="logoutmodal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Apakah Anda yakin ingin keluar ?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-footer">
          <a href="./logout.php" type="button" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Ya</a>
          <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i> Tidak</button>
        </div>
      </div>
    </div>
  </div><!-- End Logout Modal-->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.min.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.min.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="../assets/js/main.js"></script>

</body>

</html>