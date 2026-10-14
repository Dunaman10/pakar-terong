<?php
require_once __DIR__ . '/admin/sesi.php'; mulai_sesi();
include './admin/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dempster Shafer</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="./assets/img/favicon.png" rel="icon">
  <link href="./assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link href="./assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="./assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="./assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="./assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="./assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="./assets/css/style.css" rel="stylesheet">

  <style>
    .gejala-error {
      color: blue;
      padding: 5px;
      display: flex;
      justify-content: center;
    }
    .form-identitas label {
      font-weight: 600;
    }
    .section-title {
      font-weight: 600;
      color: #444;
      border-left: 4px solid #0d6efd;
      padding-left: 10px;
      margin-bottom: 16px;
    }
  </style>

</head>

<body>

  <section>
    <div class="card mt-4 col-md-7 mx-auto">
      <div class="mt-4">
        <a href="./index.php" type="button" class="btn btn-outline-danger btn-sm ms-3">
          <i class="bi bi-box-arrow-left"></i> Kembali
        </a>
      </div>
      <h4 class="text-dark d-flex justify-content-center mt-4">Proses Konsultasi Penyakit dan Hama Tanaman Terong</h4>

      <div class="card-body">

        <!-- Petunjuk -->
        <div class="bg-info rounded-1 mb-4">
          <h5 class="mt-3 ms-2 p-1">Petunjuk Pengisian</h5>
          <p class="ms-3 me-2 pb-2">Pilih gejala yang sesuai dengan kondisi yang terjadi pada tanaman. Minimal pilih 2 gejala untuk melanjutkan proses diagnosa.</p>
        </div>

        <!-- Status login -->
        <?php if (isset($_SESSION['user_id'])): ?>
          <div class="alert alert-success py-2 d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            Kamu login sebagai <strong><?= htmlspecialchars($_SESSION['user_nama']) ?></strong> — hasil konsultasi ini akan tersimpan di
            <a href="./riwayat.php">Riwayat Saya</a>.
          </div>
        <?php else: ?>
          <div class="alert alert-secondary py-2 d-flex align-items-center gap-2">
            <i class="bi bi-info-circle"></i>
            Kamu konsultasi sebagai tamu, hasilnya tidak akan tersimpan. <a href="./login.php">Login</a> atau <a href="./register.php">daftar</a> supaya hasilnya tersimpan otomatis.
          </div>
        <?php endif; ?>

        <form action="./hasilkonsultasi.php" method="POST">

          <!-- ===================== PILIH GEJALA ===================== -->
          <p class="section-title"><i class="bi bi-clipboard2-pulse"></i> Pilih Gejala</p>

          <?php
          // Tampilkan error gejala jika kurang dari 2
          if (isset($_POST['submit_konsultasi'])) {
            if (!isset($_POST['bukti']) || count($_POST['bukti']) < 2) {
              echo '<p class="gejala-error">Mohon maaf, anda harus memilih minimal 2 gejala.</p>';
            }
          }

          $sqli = "SELECT * FROM tb_gejala ORDER BY id ASC";
          $result = $koneksi->query($sqli);

          $no = 1; // ← counter nomor urut mulai dari 1
          while ($row = $result->fetch_object()) {
            $checked = '';
            if (isset($_POST['bukti']) && in_array($row->id, $_POST['bukti'])) {
              $checked = 'checked';
            }
            echo "<hr>";
            echo "<label for='checkbox{$row->id}' style='cursor: pointer; display: flex; align-items: flex-start; gap: 10px;'>";
            echo "  <input style='cursor: pointer; width:20px; height:20px; margin-top:2px; flex-shrink:0;'
                         type='checkbox'
                         id='checkbox{$row->id}'
                         name='bukti[]'
                         value='{$row->id}'
                         {$checked}>";
            echo "  <span>{$no}. {$row->gejala}</span>"; // nomor pakai $no, bukan $row->id
            echo "</label><br>";
            $no++; // increment counter
          }
          ?>
          <!-- ===================== END PILIH GEJALA ===================== -->

          <div class="mt-4 mb-2">
            <button
              type="submit"
              name="submit_konsultasi"
              class="btn btn-outline-success btn-md"
              onclick="return validateForm();"
              style="width: 130px; height: 40px; cursor: pointer;">
              <i class="bi bi-check-lg"></i> Diagnosa
            </button>
          </div>

        </form>

        <script>
          function validateForm() {
            // Validasi gejala minimal 2
            const boxes = document.getElementsByName('bukti[]');
            let checkboxesChecked = 0;
            for (let i = 0; i < boxes.length; i++) {
              if (boxes[i].checked) checkboxesChecked++;
            }
            if (checkboxesChecked < 2) {
              alert('Maaf, Anda harus memilih minimal 2 gejala.');
              return false;
            }

            return true;
          }
        </script>

      </div>
    </div>
  </section>

  <!-- Vendor JS Files -->
  <script src="./assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="./assets/js/main.js"></script>

</body>
</html>