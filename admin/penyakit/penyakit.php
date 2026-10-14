<?php include "../auth.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin - Sistem Pakar Penyakit dan Hama Tanaman Terong</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../../assets/img/favicon.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

  <!-- Template Main CSS File -->
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
    /* Styling tabel konsisten dengan halaman relasi */
    #myTable thead tr th {
      background-color: #f0f4ff;
      color: #012970;
      font-weight: 600;
      font-size: 13px;
      vertical-align: middle;
    }
    #myTable tbody tr td {
      font-size: 13px;
      vertical-align: middle;
    }
    #myTable tbody tr:hover {
      background-color: #f5f7ff;
    }
    .badge-kd {
      background-color: #e8ecff;
      color: #4154f1;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }
    #myTable td {
      word-wrap: break-word;
      white-space: normal;
    }
    #myTable td:nth-child(4),
    #myTable td:nth-child(5) {
      text-align: justify;
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
        <a class="nav-link active" href="../penyakit/penyakit.php">
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
      <h1>Data Penyakit</h1>
      <nav style="--bs-breadcrumb-divider: '>';">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../index.php">Admin</a></li>
          <li class="breadcrumb-item active">Data Penyakit</li>
        </ol>
      </nav>
      <hr>
    </div>

    <section>
      <div class="row">
        <div class="col-md-12">

          <button type="button" class="tambah btn-md mb-3" data-bs-toggle="modal" data-bs-target="#tambahmodal">
            <i class="bi bi-pencil-square"></i> Tambah Data
          </button>

          <div class="table-responsive">
            <table id="myTable" class="table table-striped table-hover table-bordered">
              <thead>
                <tr>
                  <th width="50" class="text-center">No</th>
                  <th width="110" class="text-center">Kode Penyakit</th>
                  <th>Nama Penyakit</th>
                  <th>Definisi</th>
                  <th>Solusi</th>
                  <th width="70" class="text-center">Edit</th>
                  <th width="80" class="text-center">Hapus<input type="hidden" id="texthapus"></th>
                </tr>
              </thead>
              <tbody>
                <?php
                include "../koneksi.php";
                $sql = "SELECT * FROM tb_penyakit ORDER BY CAST(SUBSTRING(kdpenyakit, 2) AS UNSIGNED)";
                $qry = mysqli_query($koneksi, $sql) or die("SQL Error" . mysqli_error($koneksi));
                $no = 0;
                while ($data = mysqli_fetch_array($qry)) {
                  $no++;
                ?>
                  <tr>
                    <td class="text-center"><?php echo $no; ?></td>
                    <td class="text-center">
                      <span class="badge-kd"><?php echo htmlspecialchars($data['kdpenyakit']); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($data['nama_penyakit']); ?></td>
                    <td><?php echo htmlspecialchars($data['definisi']); ?></td>
                    <td><?php echo htmlspecialchars($data['solusi']); ?></td>
                    <td class="text-center">
                      <a href="./editpenyakit.php?kdubah=<?php echo $data['id']; ?>"
                         class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                      </a>
                    </td>
                    <td class="text-center">
                      <a onclick="return HapusData('<?php echo $data['id']; ?>')"
                         class="btn btn-danger btn-sm"
                         data-bs-toggle="modal"
                         data-bs-target="#hapusmodal">
                        <i class="bi bi-trash"></i> Hapus
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

  <!-- Tambah Modal -->
  <div id="tambahmodal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title mx-auto">Tambah Data Penyakit</h5>
        </div>
        <div class="modal-body">
          <form method="post" action="./simpanpenyakit.php">
            <div class="form-group mb-2">
              <label for="kdpenyakit">Kode Penyakit :</label>
              <input type="text" class="form-control" id="kdpenyakit" name="kdpenyakit">
            </div>
            <div class="form-group mb-2">
              <label for="nama_penyakit">Nama Penyakit :</label>
              <input type="text" class="form-control" id="nama_penyakit" name="nama_penyakit">
            </div>
            <div class="form-group mb-2">
              <label for="definisi">Definisi Penyakit :</label>
              <textarea class="form-control" rows="3" id="definisi" name="definisi"></textarea>
            </div>
            <div class="form-group">
              <label for="solusi">Solusi Penyakit :</label>
              <textarea class="form-control" rows="3" id="solusi" name="solusi"></textarea>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary mt-4 btn-sm">Simpan</button>
              <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Batal</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

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

  <!-- Hapus Modal -->
  <div class="modal fade" id="hapusmodal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Apakah Anda ingin menghapus data?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-footer">
          <a onclick="return DropData();" class="btn btn-danger btn-sm">
            <i class="bi bi-check-lg"></i> Ya, Hapus
          </a>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
            <i class="bi bi-x"></i> Batal
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Vendor JS Files -->
  <script src="../../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
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
    $(document).ready(function () {
      $("#myTable").dataTable({
        "language": {
          "search": "Cari:",
          "lengthMenu": "Tampilkan _MENU_ data",
          "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
          "paginate": { "previous": "Sebelumnya", "next": "Berikutnya" },
          "zeroRecords": "Data tidak ditemukan",
          "infoEmpty": "Tidak ada data tersedia",
          "infoFiltered": "(difilter dari _MAX_ total data)"
        }
      });
    });

    function HapusData(xidhapus) {
      $("#texthapus").val(xidhapus);
    }

    function DropData() {
      var data_hapus = $("#texthapus").val();
      $.ajax({
        url: "./hapus.php",
        data: { data_hapus: data_hapus, aksi: "penyakit" },
        cache: false,
        success: function (msg) {
          if (msg == "sukses") {
            $('#hapusmodal').modal('hide');
            window.location.reload();
          }
        }
      });
    }

    $(function () {
      $('.text').truncatable({
        limit: 100,
        more: '[<strong style="color:red;">&raquo;&raquo;&raquo;</strong>]',
        less: true,
        hideText: '[<strong>&laquo;&laquo;&laquo;</strong>]'
      });
    });
  </script>

</body>
</html>