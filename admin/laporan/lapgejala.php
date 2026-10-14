<?php
// ── HARUS DI BARIS 1 — tidak boleh ada output apapun sebelum ini ──────────────
include "../auth.php";

// ── Aksi Hapus ───────────────────────────────────────────────────────────────
if (isset($_GET['hapus'])) {
  $id_hapus = (int) $_GET['hapus'];
  mysqli_query($koneksi, "DELETE FROM tb_history_diagnosa WHERE id = $id_hapus");
  header("Location: lapgejala.php?msg=hapus");
  exit;
}

// ── Data untuk tabel ─────────────────────────────────────────────────────────
$per_page   = 10;
$page       = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset     = ($page - 1) * $per_page;
$total_rows = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM tb_history_diagnosa"))[0];
$total_page = ceil($total_rows / $per_page);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Admin - Laporan History Diagnosa</title>

  <link href="../../assets/img/favicon.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../../assets/css/style.css" rel="stylesheet">

  <style>
    .badge-cf {
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
    }
    .badge-cf.tinggi { background-color: #d1fae5; color: #065f46; }
    .badge-cf.sedang { background-color: #fef9c3; color: #92400e; }
    .badge-cf.rendah { background-color: #fee2e2; color: #991b1b; }

    .gejala-cell {
      max-width: 220px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      display: inline-block;
    }
    .alamat-cell {
      max-width: 160px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      display: inline-block;
    }

    .id-mono {
      font-family: monospace;
      font-size: 11px;
      background: #f3f4f6;
      border: 1px solid #d1d5db;
      border-radius: 4px;
      padding: 2px 7px;
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="../index.php" class="logo d-flex align-items-center">
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
        <a class="nav-link collapsed" href="../gejala/gejala.php">
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
        <a class="nav-link active" href="./lapgejala.php">
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
      <h1>Laporan History Diagnosa</h1>
      <nav style="--bs-breadcrumb-divider: '>';">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../index.php">Admin</a></li>
          <li class="breadcrumb-item">Laporan</li>
          <li class="breadcrumb-item active">History Diagnosa</li>
        </ol>
      </nav>
      <hr>
    </div>

    <?php if (!empty($_GET['msg']) && $_GET['msg'] === 'hapus'): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>Data diagnosa berhasil dihapus.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <section>

      <!-- ── Tabel History ── -->
      <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
          <thead>
            <tr>
              <th class="text-center" width="40">No</th>
              <th>ID Laporan</th>
              <th>Nama</th>
              <th class="text-center">Umur</th>
              <th class="text-center">J.K</th>
              <th>Alamat</th>
              <th>No HP</th>
              <th>Gejala Dipilih</th>
              <th>Hasil Diagnosa</th>
              <th class="text-center">Kepercayaan</th>
              <th>Tanggal</th>
              <th class="text-center" width="60">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Join ke tb_user supaya identitas (umur, jenis kelamin, alamat, no HP)
            // ikut ditampilkan dari profil akun — hanya ada kalau konsultasi
            // dilakukan oleh user yang login (id_user tidak NULL).
            $sql_data = "SELECT h.*, u.umur, u.jenis_kelamin, u.alamat, u.no_hp,
                               (SELECT GROUP_CONCAT(g.gejala ORDER BY g.id SEPARATOR '; ')
                                FROM tb_detail_diagnosa d
                                JOIN tb_gejala g ON g.id = d.id_gejala
                                WHERE d.id_history = h.id) AS gejala_list
                         FROM tb_history_diagnosa h
                         LEFT JOIN tb_user u ON u.id = h.id_user
                         ORDER BY h.tanggal DESC LIMIT $per_page OFFSET $offset";
            $qry_data = mysqli_query($koneksi, $sql_data) or die(mysqli_error($koneksi));

            if (mysqli_num_rows($qry_data) === 0):
            ?>
              <tr>
                <td colspan="12" class="text-center text-muted py-4">
                  <i class="bi bi-inbox" style="font-size:32px; display:block; margin-bottom:8px;"></i>
                  Belum ada data history diagnosa.
                </td>
              </tr>
            <?php
            else:
              $no_tabel = $offset + 1;
              while ($row = mysqli_fetch_assoc($qry_data)):
                $id_laporan = 'DX-' . date('Ymd', strtotime($row['tanggal'])) . '-' . str_pad($row['id'], 4, '0', STR_PAD_LEFT);
                $cf = (float) $row['nilai_cf'];
                if ($cf >= 70)     $cf_class = 'tinggi';
                elseif ($cf >= 40) $cf_class = 'sedang';
                else               $cf_class = 'rendah';
            ?>
              <tr>
                <td class="text-center"><?= $no_tabel++ ?></td>
                <td><span class="id-mono"><?= htmlspecialchars($id_laporan) ?></span></td>
                <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                <td class="text-center"><?= $row['umur'] !== null ? htmlspecialchars($row['umur']) . ' thn' : '-' ?></td>
                <td class="text-center">
                  <?php if ($row['jenis_kelamin'] === 'Laki-laki'): ?>
                    <i class="bi bi-gender-male text-primary" title="Laki-laki"></i>
                  <?php elseif ($row['jenis_kelamin'] === 'Perempuan'): ?>
                    <i class="bi bi-gender-female text-danger" title="Perempuan"></i>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </td>
                <td><?= $row['alamat'] !== null ? '<span class="alamat-cell" title="' . htmlspecialchars($row['alamat']) . '">' . htmlspecialchars($row['alamat']) . '</span>' : '<span class="text-muted">-</span>' ?></td>
                <td><?= $row['no_hp'] !== null ? htmlspecialchars($row['no_hp']) : '<span class="text-muted">-</span>' ?></td>
                <td>
                  <span class="gejala-cell"
                        title="<?= htmlspecialchars($row['gejala_list'] ?? '-') ?>"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top">
                    <?= htmlspecialchars($row['gejala_list'] ?? '-') ?>
                  </span>
                </td>
                <td>
                  <span class="badge rounded-pill text-bg-primary" style="font-size:11px;">
                    <?= htmlspecialchars($row['hasil_diagnosa']) ?>
                  </span>
                </td>
                <td class="text-center">
                  <span class="badge-cf <?= $cf_class ?>"><?= $cf ?>%</span>
                </td>
                <td style="font-size:12px; white-space:nowrap;">
                  <?= date('d M Y H:i', strtotime($row['tanggal'])) ?>
                </td>
                <td class="text-center">
                  <a href="lapgejala.php?hapus=<?= $row['id'] ?>"
                     class="btn btn-danger btn-sm"
                     title="Hapus"
                     onclick="return confirm('Hapus data diagnosa <?= htmlspecialchars(addslashes($row['nama'])) ?> ini?')">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endwhile; endif; ?>
          </tbody>
        </table>
      </div>

      <!-- ── Pagination ── -->
      <?php if ($total_page > 1): ?>
      <nav class="mt-3">
        <ul class="pagination pagination-sm justify-content-center">
          <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page - 1 ?>"><i class="bi bi-chevron-left"></i></a>
          </li>
          <?php
          $start_p = max(1, $page - 2);
          $end_p   = min($total_page, $page + 2);
          for ($p = $start_p; $p <= $end_p; $p++):
          ?>
            <li class="page-item <?= $p === $page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?= $page >= $total_page ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>"><i class="bi bi-chevron-right"></i></a>
          </li>
        </ul>
      </nav>
      <?php endif; ?>

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
          <a href="../logout.php" type="button" class="btn btn-success btn-sm">
            <i class="bi bi-check-lg"></i> Ya
          </a>
          <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
            <i class="bi bi-x"></i> Tidak
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Vendor JS -->
  <script src="../../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/vendor/chart.js/chart.min.js"></script>
  <script src="../../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../../assets/vendor/quill/quill.min.js"></script>
  <script src="../../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../../assets/vendor/php-email-form/validate.js"></script>
  <script src="../../assets/js/main.js"></script>
  <script>
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
      new bootstrap.Tooltip(el, { trigger: 'hover' });
    });
  </script>

</body>
</html>