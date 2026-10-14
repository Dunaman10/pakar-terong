<?php include "../auth.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Admin - Relasi Gejala dan Penyakit</title>

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
    .tambah {
      background-color: #1d1668;
      border: none;
      color: white;
      padding: 10px 20px;
      border-radius: 3px;
    }
    .tambah:hover {
      background-color: #717ff5;
      color: white;
      padding: 10px 20px;
      border-radius: 3px;
    }
    .cekbok {
      cursor: pointer;
      width: 18px;
      height: 18px;
    }
    .badge-penyakit {
      background-color: #4154f1;
      color: white;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
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
        <a class="nav-link active" href="../relasi/relasi.php">
          <i class="bi bi-diagram-3"></i>
          <span>Relasi Gejala</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="../bobot/bobot.php">
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
      <h1>Relasi Gejala dan Penyakit</h1>
      <nav style="--bs-breadcrumb-divider: '>';">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../index.php">Admin</a></li>
          <li class="breadcrumb-item active">Relasi Gejala</li>
        </ol>
      </nav>
      <hr>
    </div>

    <?php
    // Tampilkan pesan sukses/error jika ada
    if (!empty($_GET['msg'])) {
      $msg  = htmlspecialchars($_GET['msg']);
      $type = ($_GET['type'] ?? 'success') === 'error' ? 'danger' : 'success';
      echo "<div class='alert alert-{$type} alert-dismissible fade show mx-1 mb-3' role='alert'>
              <i class='bi bi-" . ($type === 'success' ? 'check-circle' : 'exclamation-triangle') . "-fill me-2'></i>
              {$msg}
              <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>";
    }
    ?>

    <section>
      <div class="row">
        <div class="col-md-12">

          <!-- Tombol Tambah Relasi -->
          <button type="button" class="tambah btn-md mb-3" data-bs-toggle="modal" data-bs-target="#tambahmodal">
            <i class="bi bi-plus-lg"></i> Tambah Relasi
          </button>

          <!-- Tabel Data Relasi -->
          <div class="table-responsive">
            <table id="myTable" class="table table-striped table-hover table-bordered">
              <thead>
                <tr>
                  <th width="50">No</th>
                  <th>Nama Penyakit</th>
                  <th>Nama Gejala</th>
                  <th width="80" class="text-center">Edit</th>
                  <th width="80" class="text-center">Hapus<input type="hidden" id="texthapus"><input type="hidden" id="textedit_id"><input type="hidden" id="textedit_penyakit"><input type="hidden" id="textedit_gejala"></th>
                </tr>
              </thead>
              <tbody>
                <?php
                include "../koneksi.php";

                $sql = "SELECT r.id, r.id_penyakit, r.id_gejala,
                               p.kdpenyakit, p.nama_penyakit,
                               g.kdgejala, g.gejala
                        FROM tb_relasi r
                        JOIN tb_penyakit p ON r.id_penyakit = p.id
                        JOIN tb_gejala g ON r.id_gejala = g.id
                        ORDER BY CAST(SUBSTRING(p.kdpenyakit, 2) AS UNSIGNED), g.id";
                $qry = mysqli_query($koneksi, $sql) or die("SQL Error: " . mysqli_error($koneksi));
                $no = 0;
                while ($data = mysqli_fetch_array($qry)) {
                  $no++;
                ?>
                  <tr>
                    <td class="text-center"><?php echo $no; ?></td>
                    <td>
                      <span class="badge-penyakit"><?php echo htmlspecialchars($data['kdpenyakit']); ?></span>
                      &nbsp;<?php echo htmlspecialchars($data['nama_penyakit']); ?>
                    </td>
                    <td>
                      <strong><?php echo htmlspecialchars($data['kdgejala']); ?></strong>
                      &nbsp;|&nbsp;<?php echo htmlspecialchars($data['gejala']); ?>
                    </td>
                    <td class="text-center">
                      <a onclick="return EditData('<?php echo $data['id']; ?>', '<?php echo $data['id_penyakit']; ?>', '<?php echo $data['id_gejala']; ?>')"
                         class="btn btn-warning btn-sm"
                         data-bs-toggle="modal"
                         data-bs-target="#editmodal"
                         title="Edit Relasi">
                        <i class="bi bi-pencil"></i>
                      </a>
                    </td>
                    <td class="text-center">
                      <a onclick="return HapusData('<?php echo $data['id']; ?>')"
                         class="btn btn-danger btn-sm"
                         data-bs-toggle="modal"
                         data-bs-target="#hapusmodal"
                         title="Hapus Relasi">
                        <i class="bi bi-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </section>

  </main>

  <!-- ===== Modal Tambah Relasi ===== -->
  <div id="tambahmodal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mx-auto">Tambah Relasi Penyakit dan Gejala</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="./simpanrelasi.php" onsubmit="return validateForm()">

            <!-- Pilih Penyakit -->
            <div class="form-group mb-3">
              <label for="id_penyakit"><strong>Pilih Penyakit :</strong></label>
              <select class="form-select" id="id_penyakit" name="id_penyakit">
                <option value="" disabled selected>-- Pilih Penyakit --</option>
                <?php
                $sqlp = "SELECT * FROM tb_penyakit ORDER BY CAST(SUBSTRING(kdpenyakit, 2) AS UNSIGNED)";
                $qryp = mysqli_query($koneksi, $sqlp) or die("SQL Error: " . mysqli_error($koneksi));
                while ($dp = mysqli_fetch_array($qryp)) {
                  echo "<option value='{$dp['id']}'>" . htmlspecialchars($dp['kdpenyakit']) . " | " . htmlspecialchars($dp['nama_penyakit']) . "</option>";
                }
                ?>
              </select>
            </div>

            <!-- Pilih Gejala (checkbox) -->
            <div class="form-group mb-3">
              <label><strong>Pilih Gejala :</strong></label>
              <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                <?php
                $sqlg = "SELECT * FROM tb_gejala ORDER BY id";
                $qryg = mysqli_query($koneksi, $sqlg) or die("SQL Error: " . mysqli_error($koneksi));
                while ($dg = mysqli_fetch_array($qryg)) {
                ?>
                  <div class="form-check mb-1">
                    <input class="form-check-input cekbok" type="checkbox"
                           id="gejala<?php echo $dg['id']; ?>"
                           name="id_gejala[]"
                           value="<?php echo $dg['id']; ?>">
                    <label class="form-check-label" for="gejala<?php echo $dg['id']; ?>" style="cursor:pointer;">
                      <strong><?php echo htmlspecialchars($dg['kdgejala']); ?></strong>
                      &nbsp;|&nbsp;<?php echo htmlspecialchars($dg['gejala']); ?>
                    </label>
                  </div>
                <?php } ?>
              </div>
              <small class="text-muted">Pilih minimal 1 gejala</small>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
              <button type="submit" class="btn btn-primary btn-sm px-4">
                <i class="bi bi-save"></i> Simpan
              </button>
              <button type="button" class="btn btn-danger btn-sm px-4" data-bs-dismiss="modal">
                <i class="bi bi-x"></i> Batal
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End Modal Tambah -->

  <!-- ===== Modal Edit Relasi ===== -->
  <div id="editmodal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mx-auto">Edit Relasi Penyakit dan Gejala</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="post" action="./updaterelasi.php">
            <input type="hidden" name="id" id="edit_id">

            <!-- Pilih Penyakit -->
            <div class="form-group mb-3">
              <label for="edit_penyakit"><strong>Pilih Penyakit :</strong></label>
              <select class="form-select" id="edit_penyakit" name="id_penyakit">
                <?php
                $sqlp2 = "SELECT * FROM tb_penyakit ORDER BY CAST(SUBSTRING(kdpenyakit, 2) AS UNSIGNED)";
                $qryp2 = mysqli_query($koneksi, $sqlp2) or die("SQL Error: " . mysqli_error($koneksi));
                while ($dp2 = mysqli_fetch_array($qryp2)) {
                  echo "<option value='{$dp2['id']}'>" . htmlspecialchars($dp2['kdpenyakit']) . " | " . htmlspecialchars($dp2['nama_penyakit']) . "</option>";
                }
                ?>
              </select>
            </div>

            <!-- Pilih Gejala -->
            <div class="form-group mb-3">
              <label for="edit_gejala"><strong>Pilih Gejala :</strong></label>
              <select class="form-select" id="edit_gejala" name="id_gejala">
                <?php
                $sqlg2 = "SELECT * FROM tb_gejala ORDER BY id";
                $qryg2 = mysqli_query($koneksi, $sqlg2) or die("SQL Error: " . mysqli_error($koneksi));
                while ($dg2 = mysqli_fetch_array($qryg2)) {
                  echo "<option value='{$dg2['id']}'>" . htmlspecialchars($dg2['kdgejala']) . " | " . htmlspecialchars($dg2['gejala']) . "</option>";
                }
                ?>
              </select>
            </div>

            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-save"></i> Simpan Perubahan
              </button>
              <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                <i class="bi bi-x"></i> Batal
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- End Modal Edit -->

  <!-- ===== Modal Konfirmasi Hapus ===== -->
  <div class="modal fade" id="hapusmodal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hapus Relasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus relasi ini?</p>
        </div>
        <div class="modal-footer">
          <a onclick="return DropData();" class="btn btn-danger btn-sm">
            <i class="bi bi-trash"></i> Ya, Hapus
          </a>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
            <i class="bi bi-x"></i> Batal
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- End Modal Hapus -->

  <!-- ===== Logout Modal ===== -->
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
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

  <script>
    // Inisialisasi DataTable
    $(document).ready(function () {
      $("#myTable").dataTable({
        "language": {
          "search": "Cari:",
          "lengthMenu": "Tampilkan _MENU_ data",
          "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
          "paginate": { "previous": "Sebelumnya", "next": "Berikutnya" }
        }
      });
    });

    // Validasi form sebelum submit
    function validateForm() {
      var penyakit = document.getElementById("id_penyakit").value;
      if (!penyakit) {
        alert("Silakan pilih penyakit terlebih dahulu!");
        return false;
      }
      var checkboxes = document.getElementsByName("id_gejala[]");
      var count = 0;
      for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) count++;
      }
      if (count === 0) {
        alert("Pilih minimal 1 gejala!");
        return false;
      }
      return true;
    }

    // Simpan data relasi yang akan diedit
    function EditData(id, id_penyakit, id_gejala) {
      document.getElementById("edit_id").value        = id;
      document.getElementById("edit_penyakit").value  = id_penyakit;
      document.getElementById("edit_gejala").value    = id_gejala;
    }

    // Simpan ID relasi yang akan dihapus
    function HapusData(id) {
      $("#texthapus").val(id);
    }

    // Eksekusi hapus via AJAX
    function DropData() {
      var id = $("#texthapus").val();
      $.ajax({
        url: "./hapusrelasi.php",
        data: { id: id },
        cache: false,
        success: function (msg) {
          if (msg === "sukses") {
            $('#hapusmodal').modal('hide');
            window.location.reload();
          } else {
            alert("Gagal menghapus data!");
          }
        }
      });
    }
  </script>

</body>
</html>