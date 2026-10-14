<?php include "../auth.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Admin - Bobot / Belief Dempster Shafer</title>

  <link href="../../assets/img/favicon.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
  <link href="../../assets/css/style.css" rel="stylesheet">

  <style>
    .badge-penyakit {
      background-color: #4154f1;
      color: white;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }
    .badge-belum {
      background-color: #dc3545;
      color: white;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
    }
    .badge-sudah {
      background-color: #198754;
      color: white;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
    }
    .input-belief {
      width: 80px;
      display: inline-block;
      text-align: center;
    }
    .input-belief.is-invalid {
      border-color: #dc3545;
    }
    .info-box {
      background: #f0f4ff;
      border-left: 4px solid #4154f1;
      padding: 10px 15px;
      border-radius: 4px;
      margin-bottom: 20px;
      font-size: 14px;
    }
    .belief-ref-hint {
      font-size: 11px;
      margin-top: 4px;
      line-height: 1.4;
    }
    .belief-ref-hint .samakan-btn {
      font-size: 11px;
      text-decoration: underline;
      vertical-align: baseline;
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
          <i class="bi bi-house"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../penyakit/penyakit.php">
          <i class="bi bi-database"></i>
          <span>Data Penyakit</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../gejala/gejala.php">
          <i class="bi bi-clipboard2-pulse"></i>
          <span>Data Gejala</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../relasi/relasi.php">
          <i class="bi bi-diagram-3"></i>
          <span>Relasi Gejala</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link active" href="../bobot/bobot.php">
          <i class="bi bi-sliders"></i>
          <span>Bobot / Belief</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../laporan/lapgejala.php">
          <i class="bi bi-journal-text"></i>
          <span>Laporan</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="javascript:;" data-bs-toggle="modal" data-bs-target="#logoutmodal">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Logout</span>
        </a>
      </li>

    </ul>
  </aside>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Bobot / Belief Dempster Shafer</h1>
      <nav style="--bs-breadcrumb-divider: '>';">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../index.php">Admin</a></li>
          <li class="breadcrumb-item active">Bobot / Belief</li>
        </ol>
      </nav>
      <hr>
    </div>

    <?php
    if (!empty($_GET['msg'])) {
      $msg  = htmlspecialchars($_GET['msg']);
      $type = ($_GET['type'] ?? 'success') === 'error' ? 'danger' : 'success';
      $icon = $type === 'success' ? 'check-circle' : 'exclamation-triangle';
      echo "<div class='alert alert-{$type} alert-dismissible fade show mb-3' role='alert'>
              <i class='bi bi-{$icon}-fill me-2'></i>{$msg}
              <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
    }
    ?>

    <section>
      <?php
      include "../koneksi.php";

      // Ambil semua nilai belief yang sudah diisi di SEMUA penyakit (referensi silang).
      // Dipakai untuk ngingetin admin kalau ada gejala yang sama dipakai di penyakit lain
      // tapi nilai belief-nya beda — karena itu bisa bikin hasil Dempster-Shafer melenceng.
      $sql_semua_bobot = "SELECT r.id_gejala, r.id_penyakit, p.kdpenyakit, p.nama_penyakit, b.belief
                           FROM tb_relasi r
                           JOIN tb_bobot b ON b.id_penyakit = r.id_penyakit AND b.id_gejala = r.id_gejala
                           JOIN tb_penyakit p ON p.id = r.id_penyakit
                           WHERE b.belief IS NOT NULL";
      $qry_semua_bobot  = mysqli_query($koneksi, $sql_semua_bobot) or die(mysqli_error($koneksi));
      $referensi_belief = []; // [ id_gejala => [ ['id_penyakit','kdpenyakit','nama_penyakit','belief'], ... ] ]
      while ($rb = mysqli_fetch_assoc($qry_semua_bobot)) {
        $referensi_belief[$rb['id_gejala']][] = $rb;
      }

      // Ambil semua penyakit yang sudah punya relasi
      $sql_penyakit = "SELECT DISTINCT p.id, p.kdpenyakit, p.nama_penyakit
                       FROM tb_relasi r
                       JOIN tb_penyakit p ON r.id_penyakit = p.id
                       ORDER BY CAST(SUBSTRING(p.kdpenyakit, 2) AS UNSIGNED)";
      $qry_penyakit = mysqli_query($koneksi, $sql_penyakit) or die(mysqli_error($koneksi));

      if (mysqli_num_rows($qry_penyakit) == 0) {
      ?>
        <div class="alert alert-warning">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          Belum ada data relasi. Silakan tambahkan relasi gejala terlebih dahulu di
          <a href="../relasi/relasi.php">halaman Relasi Gejala</a>.
        </div>
      <?php
      } else {
      ?>
        <div class="info-box">
          <i class="bi bi-info-circle-fill me-2"></i>
          Masukkan nilai belief untuk setiap gejala pada masing-masing penyakit.
          Nilai belief harus berupa angka antara <strong>0</strong> dan <strong>1</strong> (contoh: 0.8).
          Klik <strong>Simpan</strong> pada tiap penyakit setelah selesai mengisi.
        </div>

        <?php while ($penyakit = mysqli_fetch_array($qry_penyakit)) {
          $id_penyakit   = $penyakit['id'];
          $kdpenyakit    = $penyakit['kdpenyakit'];
          $nama_penyakit = $penyakit['nama_penyakit'];

          // Ambil semua gejala yang sudah direlasikan ke penyakit ini
          $sql_gejala = "SELECT r.id AS id_relasi, r.id_gejala,
                                g.kdgejala, g.gejala,
                                b.belief, b.id AS id_bobot
                         FROM tb_relasi r
                         JOIN tb_gejala g ON r.id_gejala = g.id
                         LEFT JOIN tb_bobot b ON b.id_penyakit = r.id_penyakit AND b.id_gejala = r.id_gejala
                         WHERE r.id_penyakit = '$id_penyakit'
                         ORDER BY g.id";
          $qry_gejala = mysqli_query($koneksi, $sql_gejala) or die(mysqli_error($koneksi));

          // Hitung sudah berapa gejala yang sudah punya bobot
          $total   = mysqli_num_rows($qry_gejala);
          $sudah   = 0;
          $rows    = [];
          while ($row = mysqli_fetch_assoc($qry_gejala)) {
            if ($row['belief'] !== null) $sudah++;
            $rows[] = $row;
          }
          $belum = $total - $sudah;
        ?>

          <div class="card mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
              <div>
                <span class="badge-penyakit"><?php echo htmlspecialchars($kdpenyakit); ?></span>
                <strong class="ms-2"><?php echo htmlspecialchars($nama_penyakit); ?></strong>
              </div>
              <div>
                <?php if ($belum > 0): ?>
                  <span class="badge-belum"><i class="bi bi-exclamation-circle"></i> <?php echo $belum; ?> belum diisi</span>
                <?php else: ?>
                  <span class="badge-sudah"><i class="bi bi-check-circle"></i> Semua sudah terisi</span>
                <?php endif; ?>
              </div>
            </div>
            <div class="card-body">
              <form method="post" action="./simpanbobot.php"
                    onsubmit="return validateBobot(this)">
                <input type="hidden" name="id_penyakit" value="<?php echo $id_penyakit; ?>">
                <div class="table-responsive">
                  <table class="table table-bordered table-hover mb-3">
                    <thead class="table-light">
                      <tr>
                        <th width="50" class="text-center">No</th>
                        <th>Kode Gejala</th>
                        <th>Nama Gejala</th>
                        <th width="140" class="text-center">Nilai Belief (0-1)</th>
                        <th width="80" class="text-center">Hapus</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 0; foreach ($rows as $row):
                        $no++;
                        $nilaiBeliefAda = $row['belief'] !== null;

                        // ── Cek referensi belief gejala ini di penyakit LAIN ──
                        $ref_lain = [];
                        if (isset($referensi_belief[$row['id_gejala']])) {
                          foreach ($referensi_belief[$row['id_gejala']] as $rb) {
                            if ($rb['id_penyakit'] != $id_penyakit) {
                              $ref_lain[] = $rb;
                            }
                          }
                        }
                        // Cek apakah semua referensi di penyakit lain nilainya konsisten satu sama lain
                        $ref_values = array_unique(array_map(function ($rb) { return (string) $rb['belief']; }, $ref_lain));
                        $ref_konsisten = count($ref_values) <= 1;
                        $ref_belief_value = $ref_konsisten && !empty($ref_lain) ? $ref_lain[0]['belief'] : '';
                      ?>
                        <tr>
                          <td class="text-center"><?php echo $no; ?></td>
                          <td><strong><?php echo htmlspecialchars($row['kdgejala']); ?></strong></td>
                          <td><?php echo htmlspecialchars($row['gejala']); ?></td>
                          <td class="text-center">
                            <!-- id_bobot hidden untuk keperluan update -->
                            <input type="hidden"
                                   name="id_bobot[]"
                                   value="<?php echo $row['id_bobot'] ?? ''; ?>">
                            <input type="hidden"
                                   name="id_gejala[]"
                                   value="<?php echo $row['id_gejala']; ?>">
                            <input type="text"
                                   class="form-control form-control-sm input-belief text-center"
                                   name="belief[]"
                                   value="<?php echo $nilaiBeliefAda ? htmlspecialchars($row['belief']) : ''; ?>"
                                   placeholder="0 - 1"
                                   autocomplete="off"
                                   data-gejala="<?php echo htmlspecialchars($row['kdgejala'] . ' - ' . $row['gejala']); ?>"
                                   <?php if ($ref_konsisten && $ref_belief_value !== ''): ?>
                                   data-ref-belief="<?php echo htmlspecialchars($ref_belief_value); ?>"
                                   <?php endif; ?>>

                            <?php if (!empty($ref_lain)): ?>
                              <?php if ($ref_konsisten): ?>
                                <div class="belief-ref-hint text-info">
                                  <i class="bi bi-link-45deg"></i>
                                  Sama dgn <?php echo htmlspecialchars(implode(', ', array_map(function ($rb) { return $rb['kdpenyakit']; }, $ref_lain))); ?>:
                                  <strong><?php echo htmlspecialchars($ref_belief_value); ?></strong>
                                  <button type="button" class="btn btn-link btn-sm p-0 samakan-btn" data-value="<?php echo htmlspecialchars($ref_belief_value); ?>">Samakan</button>
                                </div>
                              <?php else: ?>
                                <div class="belief-ref-hint text-warning">
                                  <i class="bi bi-exclamation-triangle"></i>
                                  Beda-beda di penyakit lain:
                                  <?php foreach ($ref_lain as $rb): ?>
                                    <?php echo htmlspecialchars($rb['kdpenyakit'] . '=' . $rb['belief']); ?>&nbsp;
                                  <?php endforeach; ?>
                                  — cek ulang manual
                                </div>
                              <?php endif; ?>
                            <?php endif; ?>
                          </td>
                          <td class="text-center">
                            <?php if ($nilaiBeliefAda): ?>
                              <a href="./hapusbobot.php?id=<?php echo $row['id_bobot']; ?>"
                                 class="btn btn-danger btn-sm"
                                 title="Hapus Bobot"
                                 onclick="return confirm('Hapus nilai belief gejala \'<?php echo htmlspecialchars($row['kdgejala']); ?>\' ini?\nNilai belief akan dihapus, relasi tetap ada.')">
                                <i class="bi bi-trash"></i>
                              </a>
                            <?php else: ?>
                              <span class="text-muted" title="Belum ada bobot">-</span>
                            <?php endif; ?>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary btn-sm px-4">
                    <i class="bi bi-save"></i> Simpan Bobot
                  </button>
                </div>
              </form>
            </div>
          </div>

        <?php } // end while penyakit ?>

      <?php } // end else ?>
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
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../../assets/vendor/chart.js/chart.min.js"></script>
  <script src="../../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../../assets/vendor/quill/quill.min.js"></script>
  <script src="../../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../../assets/vendor/php-email-form/validate.js"></script>
  <script src="../../assets/js/jquery.min.js"></script>
  <script src="../../assets/js/jquery.truncatable.js"></script>
  <script src="../../assets/js/main.js"></script>

  <script>
    function validateBobot(form) {
      var inputs = form.querySelectorAll('input.input-belief');
      var valid  = true;
      var perluKonfirmasi = [];

      inputs.forEach(function (input) {
        input.classList.remove('is-invalid');
        var val = input.value.trim();

        // Boleh kosong (belum diisi), tapi kalau diisi harus valid
        if (val !== '') {
          var num = parseFloat(val);
          if (isNaN(num) || num < 0 || num > 1) {
            input.classList.add('is-invalid');
            valid = false;
            return;
          }

          // Cek konsistensi sama belief gejala yang sama di penyakit lain
          var refBelief = input.getAttribute('data-ref-belief');
          if (refBelief !== null && parseFloat(refBelief) !== num) {
            perluKonfirmasi.push({
              gejala: input.getAttribute('data-gejala'),
              input: num,
              referensi: refBelief
            });
          }
        }
      });

      if (!valid) {
        alert("Nilai belief harus berupa angka antara 0 dan 1!\nContoh: 0.8");
        return false;
      }

      if (perluKonfirmasi.length > 0) {
        var pesan = "Beberapa gejala punya nilai belief yang beda dengan penyakit lain:\n\n";
        perluKonfirmasi.forEach(function (item) {
          pesan += "- " + item.gejala + ": kamu isi " + item.input + ", tapi di penyakit lain " + item.referensi + "\n";
        });
        pesan += "\nIni bisa bikin hasil diagnosa Dempster-Shafer kurang akurat. Tetap simpan?";
        return confirm(pesan);
      }

      return true;
    }

    // Hilangkan highlight merah saat user mengetik
    document.querySelectorAll('input.input-belief').forEach(function (input) {
      input.addEventListener('input', function () {
        this.classList.remove('is-invalid');
      });
    });

    // Tombol "Samakan" — isi otomatis dengan nilai referensi dari penyakit lain
    document.querySelectorAll('.samakan-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var input = this.closest('td').querySelector('input.input-belief');
        input.value = this.getAttribute('data-value');
        input.classList.remove('is-invalid');
      });
    });
  </script>

</body>
</html>