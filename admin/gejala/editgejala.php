<?php
include "../auth.php";

// ── Ambil data gejala berdasarkan ID ─────────────────────────────────────────
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  header("Location: ./gejala.php");
  exit;
}

// ── Proses UPDATE jika form disubmit ─────────────────────────────────────────
if (isset($_POST['simpan'])) {
  $kdgejala = trim($_POST['kdgejala'] ?? '');
  $gejala   = trim($_POST['gejala'] ?? '');
  $id_post  = (int) ($_POST['id'] ?? 0);

  if ($kdgejala === '' || $gejala === '' || mb_strlen($kdgejala) > 3 || $id_post <= 0) {
    $error = "Kode dan nama gejala wajib diisi (kode maksimal 3 karakter).";
  } else {
    // Cek apakah kode gejala sudah dipakai oleh data LAIN
    $cek = $koneksi->prepare("SELECT id FROM tb_gejala WHERE kdgejala = ? AND id != ?");
    $cek->bind_param("si", $kdgejala, $id_post);
    $cek->execute();
    $cek->store_result();
    $dipakai = $cek->num_rows > 0;
    $cek->close();

    if ($dipakai) {
      $error = "Kode gejala <strong>" . htmlspecialchars($kdgejala) . "</strong> sudah digunakan oleh data lain. Gunakan kode yang berbeda.";
    } else {
      $upd = $koneksi->prepare("UPDATE tb_gejala SET kdgejala = ?, gejala = ? WHERE id = ?");
      $upd->bind_param("ssi", $kdgejala, $gejala, $id_post);
      $ok = $upd->execute();
      $upd->close();
      if ($ok) {
        header("Location: ./gejala.php?msg=edit_berhasil");
        exit;
      }
      $error = "Gagal mengupdate data. Silakan coba lagi.";
    }
  }
}

// ── Ambil data lama untuk ditampilkan di form ─────────────────────────────────
$stmt = $koneksi->prepare("SELECT id, kdgejala, gejala FROM tb_gejala WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($d_id, $d_kd, $d_gejala);
$ada  = $stmt->fetch();
$stmt->close();

if (!$ada) {
  header("Location: ./gejala.php");
  exit;
}
$data = ['id' => $d_id, 'kdgejala' => $d_kd, 'gejala' => $d_gejala];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Edit Data Gejala</title>
  <link href="../../assets/img/favicon.png" rel="icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Poppins:300,400,500,600,700" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="../../admin/index.php" class="logo d-flex align-items-center">
        <span class="d-none d-lg-block">Sistem Pakar</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
  </header>

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link collapsed" href="../index.php">
          <i class="bi bi-house"></i><span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="../penyakit/penyakit.php">
          <i class="bi bi-database"></i><span>Data Penyakit</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="./gejala.php">
          <i class="bi bi-clipboard2-pulse"></i><span>Data Gejala</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="../relasi/relasi.php">
          <i class="bi bi-diagram-3"></i><span>Relasi Gejala</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="../bobot/bobot.php">
          <i class="bi bi-sliders"></i><span>Bobot / Belief</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="../laporan/lapgejala.php">
          <i class="bi bi-journal-text"></i><span>Laporan</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="javascript:;" data-bs-toggle="modal" data-bs-target="#logoutmodal">
          <i class="bi bi-box-arrow-in-right"></i><span>Logout</span>
        </a>
      </li>
    </ul>
  </aside>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Edit Data Gejala</h1>
      <nav style="--bs-breadcrumb-divider: '>';">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../index.php">Admin</a></li>
          <li class="breadcrumb-item"><a href="./gejala.php">Data Gejala</a></li>
          <li class="breadcrumb-item active">Edit</li>
        </ol>
      </nav>
      <hr>
    </div>

    <section>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-body p-4">

              <h5 class="card-title mb-4">
                <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Data Gejala
              </h5>

              <?php if (!empty($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $error ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php endif; ?>

              <form method="POST" action="editgejala.php?id=<?= $id ?>">
                <input type="hidden" name="id" value="<?= $data['id'] ?>">

                <div class="mb-3">
                  <label for="kdgejala" class="form-label fw-semibold">
                    Kode Gejala <span class="text-danger">*</span>
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    id="kdgejala"
                    name="kdgejala"
                    value="<?= htmlspecialchars($_POST['kdgejala'] ?? $data['kdgejala']) ?>"
                    placeholder="Contoh: G1"
                    required>
                  <div class="form-text text-muted">Kode gejala harus unik.</div>
                </div>

                <div class="mb-4">
                  <label for="gejala" class="form-label fw-semibold">
                    Nama Gejala <span class="text-danger">*</span>
                  </label>
                  <input
                    type="text"
                    class="form-control"
                    id="gejala"
                    name="gejala"
                    value="<?= htmlspecialchars($_POST['gejala'] ?? $data['gejala']) ?>"
                    placeholder="Masukkan nama gejala"
                    required>
                </div>

                <div class="d-flex gap-2">
                  <button type="submit" name="simpan" class="btn btn-primary btn-sm px-4">
                    <i class="bi bi-check-lg me-1"></i> Update
                  </button>
                  <a href="./gejala.php" class="btn btn-danger btn-sm px-4">
                    <i class="bi bi-x-lg me-1"></i> Batal
                  </a>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <!-- Logout Modal -->
  <div class="modal fade" id="logoutmodal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Apakah Anda yakin ingin keluar?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-footer">
          <a href="../logout.php" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Ya</a>
          <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
            <i class="bi bi-x"></i> Tidak
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/js/main.js"></script>
</body>
</html>