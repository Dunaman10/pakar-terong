<?php
include './admin/koneksi.php';
mulai_sesi();

// ── Redirect jika tidak ada POST ──────────────────────────────────────────────
if (!isset($_POST['bukti'])) {
  header("Location: ./konsultasi.php");
  exit;
}

// ── Data identitas (hanya untuk user yang login) ─────────────────────────────
// Tamu tidak perlu identitas apapun karena hasilnya memang tidak disimpan.
// Identitas lengkap (umur, jenis kelamin, alamat, no HP) sudah ada di profil
// akun (tb_user) — ditampilkan di dashboard admin lewat join id_user, tidak
// perlu disimpan ulang di tb_history_diagnosa.
$nama = null;
if (isset($_SESSION['user_id'])) {
  $nama = mysqli_real_escape_string($koneksi, $_SESSION['user_nama']);
}
$bukti_ids = array_map('intval', $_POST['bukti']);

// ── Ambil nama gejala yang dipilih ───────────────────────────────────────────
$gejala_list = [];
foreach ($bukti_ids as $id_g) {
  $qry = mysqli_query($koneksi, "SELECT gejala FROM tb_gejala WHERE id = $id_g");
  if ($row_g = mysqli_fetch_assoc($qry)) {
    $gejala_list[] = $row_g['gejala'];
  }
}

// ── Ambil BPA per gejala dari tb_relasi + tb_bobot ───────────────────────────
//
// Struktur yang benar berdasarkan bobot.php:
//   tb_relasi  : id_penyakit, id_gejala          → relasi M:N
//   tb_bobot   : id_penyakit, id_gejala, belief  → nilai belief
//   tb_penyakit: id, kdpenyakit, nama_penyakit
//
// Untuk setiap gejala yang dipilih, kita ambil:
//   - kode penyakit mana saja yang terkait gejala itu (GROUP_CONCAT)
//   - belief-nya dari tb_bobot
// Digroup per id_gejala → satu baris = satu sumber bukti BPA
//
$id_list = implode(',', $bukti_ids);

$sql = "
  SELECT
    r.id_gejala,
    GROUP_CONCAT(p.kdpenyakit ORDER BY p.kdpenyakit SEPARATOR ',') AS kd_penyakit,
    b.belief
  FROM tb_relasi r
  JOIN tb_penyakit p  ON r.id_penyakit = p.id
  JOIN tb_bobot   b  ON b.id_penyakit  = r.id_penyakit
                     AND b.id_gejala   = r.id_gejala
  WHERE r.id_gejala IN ($id_list)
    AND b.belief IS NOT NULL
  GROUP BY r.id_gejala, b.belief
  ORDER BY r.id_gejala
";
$result      = $koneksi->query($sql);
$sumber_bukti = [];
while ($row = $result->fetch_assoc()) {
  $sumber_bukti[] = [
    'kd'     => $row['kd_penyakit'],   // misal "P1" atau "P1,P2"
    'belief' => (float) $row['belief'],
  ];
}

// ── Cek: ada gejala yang belum punya bobot? ───────────────────────────────────
// Jika sumber_bukti kosong = semua gejala belum diisi bobot di admin
$gejala_tanpa_bobot = [];
if (empty($sumber_bukti)) {
  // Fallback info ke user
  $gejala_tanpa_bobot = $gejala_list;
}

// ── Frame of Discernment (θ) ──────────────────────────────────────────────────
$sql_fod = "SELECT GROUP_CONCAT(kdpenyakit ORDER BY kdpenyakit) FROM tb_penyakit";
$res_fod  = $koneksi->query($sql_fod);
$row_fod  = $res_fod->fetch_row();
$FOD      = $row_fod[0]; // misal "P1,P2,P3,P4"

// ── Fungsi Dempster-Shafer ────────────────────────────────────────────────────
//
// Representasi BPA: array associative [ 'kd_penyakit' => m_value ]
// Frame of Discernment (θ) disimpan dengan key '__theta__'
//
// Dempster's combination rule:
//   m12(A) = Σ_{B∩C=A} m1(B)·m2(C) / (1 - K)
//   K      = Σ_{B∩C=∅} m1(B)·m2(C)  ← total konflik
//
function bpa_dari_sumber(array $sumber, string $fod): array {
  return [
    $sumber['kd'] => $sumber['belief'],
    '__theta__'   => 1.0 - $sumber['belief'],
  ];
}

function kombinasikan_ds(array $m1, array $m2): array {
  $m_baru = [];
  $K      = 0.0; // akumulasi konflik

  foreach ($m1 as $A => $mA) {
    foreach ($m2 as $B => $mB) {

      // Hitung irisan A ∩ B
      if ($A === '__theta__' && $B === '__theta__') {
        // θ ∩ θ = θ
        $interseksi = '__theta__';
      } elseif ($A === '__theta__') {
        // θ ∩ B = B
        $interseksi = $B;
      } elseif ($B === '__theta__') {
        // A ∩ θ = A
        $interseksi = $A;
      } else {
        // Keduanya himpunan konkret
        $setA   = explode(',', $A);
        $setB   = explode(',', $B);
        sort($setA);
        sort($setB);
        $irisan = array_intersect($setA, $setB);
        $interseksi = empty($irisan) ? null : implode(',', $irisan);
      }

      $nilai = $mA * $mB;

      if ($interseksi === null) {
        // Irisan kosong = konflik
        $K += $nilai;
      } else {
        $m_baru[$interseksi] = ($m_baru[$interseksi] ?? 0.0) + $nilai;
      }
    }
  }

  // Normalisasi: bagi semua (kecuali konflik) dengan (1 - K)
  // Dilakukan SEKALI di sini, setelah semua kombinasi selesai
  $faktor = 1.0 - $K;
  if ($faktor <= 1e-9) {
    // Konflik total — tidak bisa kombinasi
    return ['__theta__' => 1.0];
  }

  foreach ($m_baru as $k => $v) {
    $m_baru[$k] = $v / $faktor;
  }

  return $m_baru;
}

// ── Jalankan kombinasi berurutan ──────────────────────────────────────────────
$m_kumulatif = null;
$ada_hasil   = !empty($sumber_bukti);

if ($ada_hasil) {
  foreach ($sumber_bukti as $sumber) {
    $m_baru_sumber = bpa_dari_sumber($sumber, $FOD);
    if ($m_kumulatif === null) {
      $m_kumulatif = $m_baru_sumber;
    } else {
      $m_kumulatif = kombinasikan_ds($m_kumulatif, $m_baru_sumber);
    }
  }

  // Hapus θ dari hasil akhir, urutkan dari belief tertinggi
  unset($m_kumulatif['__theta__']);
  arsort($m_kumulatif);

  // Hasil teratas
  $hasil_kode   = array_key_first($m_kumulatif);
  $hasil_belief = round($m_kumulatif[$hasil_kode] * 100, 2);
  $final_codes  = array_map('trim', explode(',', $hasil_kode));

  // Nama penyakit hasil teratas
  $sql_nama = "SELECT GROUP_CONCAT(nama_penyakit SEPARATOR ' & ') AS np
               FROM tb_penyakit
               WHERE kdpenyakit IN('" . implode("','", array_map(fn($c) => mysqli_real_escape_string($koneksi, $c), $final_codes)) . "')";
  $res_nama             = $koneksi->query($sql_nama);
  $row_nama             = mysqli_fetch_assoc($res_nama);
  $nama_penyakit_str    = $row_nama['np'] ?? '-';

  // ── Simpan ke history diagnosa (HANYA kalau user sedang login) ──────────────
  // Tamu tidak punya identitas apapun, jadi hasil konsultasinya tidak disimpan sama sekali.
  if (isset($_SESSION['user_id'])) {
    $hasil_esc  = mysqli_real_escape_string($koneksi, $nama_penyakit_str);
    $id_user    = (int) $_SESSION['user_id'];

    // gejala_dipilih SUDAH DIHAPUS dari tabel (kolomnya di-drop) — jangan
    // ditulis lagi di sini. Data gejala sekarang cuma disimpan lewat
    // tb_detail_diagnosa di bawah (1NF).
    $stmt_history = $koneksi->prepare(
      "INSERT INTO tb_history_diagnosa (id_user, nama, hasil_diagnosa, nilai_cf)
       VALUES (?, ?, ?, ?)"
    );
    $stmt_history->bind_param("issd", $id_user, $nama, $hasil_esc, $hasil_belief);

    if (!$stmt_history->execute()) {
      // Kalau insert gagal, jangan lanjut ke tb_detail_diagnosa (bakal
      // kena error FK karena id_history-nya gak valid) dan jangan pura-pura
      // sukses ke user.
      error_log('Gagal menyimpan riwayat diagnosa: ' . $stmt_history->error);
      die("Gagal menyimpan riwayat diagnosa. Silakan coba lagi.");
    }

    $id_history = $stmt_history->insert_id;
    $stmt_history->close();
    $id_laporan = 'DX-' . date('Ymd') . '-' . str_pad($id_history, 4, '0', STR_PAD_LEFT);

    // ── Simpan gejala ke tb_detail_diagnosa (1 baris = 1 gejala) ──────────────
    $stmt_detail = $koneksi->prepare("INSERT INTO tb_detail_diagnosa (id_history, id_gejala) VALUES (?, ?)");
    foreach ($bukti_ids as $id_g_detail) {
      $stmt_detail->bind_param("ii", $id_history, $id_g_detail);
      $stmt_detail->execute();
    }
    $stmt_detail->close();
  } else {
    $id_laporan = null;
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Hasil Diagnosa – Sistem Pakar Tanaman Terong</title>
  <link href="./assets/img/favicon.png" rel="icon">
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
  <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
  <link href="./assets/css/style.css" rel="stylesheet">

  <style>
    body { background: #f0f4f8; font-family: 'Poppins', sans-serif; }
    .hasil-wrapper { max-width: 780px; margin: 40px auto; padding: 0 16px 60px; }

    /* Header */
    .page-header {
      background: linear-gradient(135deg, #1a7f5a 0%, #0d5c40 100%);
      border-radius: 16px; padding: 28px 32px; color: #fff;
      margin-bottom: 24px; position: relative; overflow: hidden;
    }
    .page-header::before {
      content: ''; position: absolute; top: -40px; right: -40px;
      width: 180px; height: 180px; border-radius: 50%;
      background: rgba(255,255,255,0.07);
    }
    .page-header h1 { font-size: 1.25rem; font-weight: 600; margin: 0 0 4px; }
    .page-header p  { font-size: 0.82rem; opacity: 0.8; margin: 0; }
    .badge-id {
      display: inline-block; background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255,255,255,0.25); border-radius: 20px;
      padding: 3px 14px; font-size: 0.78rem; margin-bottom: 10px; letter-spacing: 0.5px;
    }

    /* Cards */
    .card-section {
      background: #fff; border-radius: 14px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.06);
      padding: 24px 28px; margin-bottom: 18px;
    }
    .card-section h5 {
      font-size: 0.82rem; font-weight: 600; text-transform: uppercase;
      letter-spacing: 0.07em; color: #6b7280; margin-bottom: 16px;
      display: flex; align-items: center; gap: 8px;
    }
    .card-section h5 i { color: #1a7f5a; font-size: 1rem; }

    /* Identitas */
    .identitas-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 24px; }
    .identitas-item .lbl { font-size: 0.72rem; color: #9ca3af; margin-bottom: 2px; }
    .identitas-item .val { font-size: 0.92rem; font-weight: 500; color: #111827; }

    /* Gejala */
    .gejala-item {
      display: flex; align-items: flex-start; gap: 10px;
      padding: 9px 0; border-bottom: 1px solid #f3f4f6;
      font-size: 0.88rem; color: #374151;
    }
    .gejala-item:last-child { border-bottom: none; }
    .gejala-no {
      min-width: 26px; height: 26px; background: #e6f4ef; color: #1a7f5a;
      border-radius: 6px; font-size: 0.75rem; font-weight: 600;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }

    /* Hasil utama */
    .hasil-utama {
      background: linear-gradient(135deg, #e6f4ef, #d1ece1);
      border: 1.5px solid #a3d9be; border-radius: 14px;
      padding: 28px 24px; text-align: center; margin-bottom: 18px;
    }
    .hasil-utama .label {
      font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
      letter-spacing: 0.08em; color: #0d5c40; margin-bottom: 8px;
    }
    .hasil-utama .penyakit-nama { font-size: 1.4rem; font-weight: 700; color: #0d5c40; margin-bottom: 18px; }
    .cf-bar-wrap {
      background: rgba(255,255,255,0.6); border-radius: 20px; height: 12px;
      margin: 0 auto 10px; max-width: 320px; overflow: hidden;
    }
    .cf-bar-fill {
      height: 100%; border-radius: 20px;
      background: linear-gradient(90deg, #1a7f5a, #29c68a);
      transition: width 1.2s ease;
    }
    .cf-pct { font-size: 1.6rem; font-weight: 700; color: #0d5c40; }
    .cf-sub { font-size: 0.75rem; color: #4b7a62; margin-top: 2px; }

    /* Semua hipotesis */
    .hipo-item {
      display: flex; align-items: center; justify-content: space-between;
      padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 0.87rem;
    }
    .hipo-item:last-child { border-bottom: none; }
    .hipo-bar-wrap {
      flex: 1; background: #f3f4f6; border-radius: 10px;
      height: 7px; margin: 0 14px; overflow: hidden;
    }
    .hipo-bar-fill { height: 100%; border-radius: 10px; background: #1a7f5a; }
    .hipo-pct { min-width: 52px; text-align: right; font-weight: 600; color: #374151; font-size: 0.84rem; }
    .hipo-kode { min-width: 36px; font-size: 0.76rem; font-weight: 600; color: #6b7280; }

    /* Saran */
    .saran-box {
      background: #fffbeb; border: 1px solid #fde68a;
      border-left: 4px solid #f59e0b; border-radius: 10px;
      padding: 16px 18px; margin-bottom: 14px;
    }
    .saran-box .saran-title {
      font-size: 0.88rem; font-weight: 600; color: #92400e;
      margin-bottom: 8px; display: flex; align-items: center; gap: 7px;
    }
    .saran-box .saran-isi { font-size: 0.87rem; color: #78350f; line-height: 1.75; white-space: pre-line; }

    /* Warning box */
    .warn-box {
      background: #fff7ed; border: 1px solid #fed7aa;
      border-left: 4px solid #f97316; border-radius: 10px;
      padding: 16px 18px; font-size: 0.88rem; color: #7c2d12; margin-bottom: 18px;
    }

    /* Tombol */
    .btn-kembali {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 24px; border-radius: 10px;
      border: 1.5px solid #d1d5db; background: #fff;
      color: #374151; font-size: 0.88rem; font-weight: 500;
      text-decoration: none; transition: all 0.15s;
    }
    .btn-kembali:hover { background: #f3f4f6; color: #111827; }
  </style>
</head>
<body>
<div class="hasil-wrapper">

  <!-- ── Header ── -->
  <div class="page-header">
    <?php if ($ada_hasil && $id_laporan): ?>
      <div class="badge-id"><i class="bi bi-qr-code"></i> <?= htmlspecialchars($id_laporan) ?></div>
    <?php endif; ?>
    <h1>Hasil Diagnosa Sistem Pakar</h1>
    <p>Penyakit dan Hama Tanaman Terong &nbsp;·&nbsp; <?= date('d M Y, H:i') ?> WIB</p>
  </div>

  <!-- ── Identitas ── -->
  <?php if ($nama): ?>
    <div class="card-section">
      <h5><i class="bi bi-person-badge"></i> Konsultasi oleh</h5>
      <div class="identitas-grid">
        <div class="identitas-item">
          <div class="lbl">Nama</div>
          <div class="val"><?= htmlspecialchars($nama) ?></div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- ── Gejala yang dipilih ── -->
  <div class="card-section">
    <h5><i class="bi bi-clipboard2-pulse"></i> Gejala yang Dipilih (<?= count($gejala_list) ?> gejala)</h5>
    <?php $no_g = 1; foreach ($gejala_list as $g): ?>
      <div class="gejala-item">
        <div class="gejala-no"><?= $no_g++ ?></div>
        <div><?= htmlspecialchars($g) ?></div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if (!$ada_hasil): ?>
    <!-- ── Peringatan: tidak ada bobot ── -->
    <div class="warn-box">
      <strong><i class="bi bi-exclamation-triangle-fill"></i> Diagnosa tidak dapat dilakukan.</strong><br>
      Gejala yang dipilih belum memiliki nilai belief di database.
      Silakan hubungi admin untuk mengisi bobot/belief terlebih dahulu.
    </div>

  <?php else: ?>

    <!-- ── Hasil Diagnosa Utama ── -->
    <div class="hasil-utama">
      <div class="label"><i class="bi bi-virus2"></i> Terdeteksi</div>
      <div class="penyakit-nama"><?= htmlspecialchars($nama_penyakit_str) ?></div>
      <div class="cf-bar-wrap">
        <div class="cf-bar-fill" id="cfBar" style="width: 0%"></div>
      </div>
      <div class="cf-pct"><?= $hasil_belief ?>%</div>
      <div class="cf-sub">Derajat Kepercayaan (Belief)</div>
    </div>

    <!-- ── Semua Hipotesis ── -->
    <?php if (count($m_kumulatif) > 1): ?>
    <div class="card-section">
      <h5><i class="bi bi-bar-chart-steps"></i> Semua Hipotesis (Ranking Belief)</h5>
      <?php
      $rank = 1;
      foreach ($m_kumulatif as $kd => $val):
        $kd_parts = array_map('trim', explode(',', $kd));
        $res_h    = $koneksi->query("SELECT GROUP_CONCAT(nama_penyakit SEPARATOR ' & ') AS np FROM tb_penyakit WHERE kdpenyakit IN('" . implode("','", array_map(fn($c) => mysqli_real_escape_string($koneksi, $c), $kd_parts)) . "')");
        $row_h    = mysqli_fetch_assoc($res_h);
        $pct_h    = round($val * 100, 2);
        $opacity  = max(0.3, 1 - ($rank - 1) * 0.18);
      ?>
      <div class="hipo-item">
        <span class="hipo-kode"><?= htmlspecialchars($kd) ?></span>
        <span style="flex:2; font-size:0.84rem; color:#374151;"><?= htmlspecialchars($row_h['np'] ?? '-') ?></span>
        <div class="hipo-bar-wrap">
          <div class="hipo-bar-fill" style="width:<?= $pct_h ?>%; opacity:<?= $opacity ?>;"></div>
        </div>
        <span class="hipo-pct"><?= $pct_h ?>%</span>
      </div>
      <?php $rank++; endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ── Saran / Solusi ── -->
    <div class="card-section">
      <h5><i class="bi bi-lightbulb"></i> Saran Penanganan</h5>
      <?php foreach ($final_codes as $kode):
        $kode    = mysqli_real_escape_string($koneksi, trim($kode));
        $res_sol = mysqli_query($koneksi, "SELECT nama_penyakit, solusi FROM tb_penyakit WHERE kdpenyakit = '$kode'");
        $sol     = mysqli_fetch_assoc($res_sol);
        if ($sol):
      ?>
      <div class="saran-box">
        <div class="saran-title"><i class="bi bi-patch-check"></i> <?= htmlspecialchars($sol['nama_penyakit']) ?></div>
        <div class="saran-isi"><?= htmlspecialchars($sol['solusi']) ?></div>
      </div>
      <?php endif; endforeach; ?>
    </div>

  <?php endif; ?>

  <!-- ── Info penyimpanan riwayat ── -->
  <?php if ($ada_hasil): ?>
    <?php if (isset($_SESSION['user_id'])): ?>
      <div class="text-center mb-3" style="font-size:0.85rem; color:#0d5c40;">
        <i class="bi bi-check-circle-fill"></i> Hasil ini sudah tersimpan di
        <a href="./riwayat.php" style="color:#0d5c40; font-weight:600;">Riwayat Saya</a>.
      </div>
    <?php else: ?>
      <div class="text-center mb-3" style="font-size:0.85rem; color:#6b7280;">
        <i class="bi bi-info-circle"></i> Kamu konsultasi sebagai tamu, hasil ini tidak tersimpan.
        <a href="./login.php" style="color:#1a7f5a; font-weight:600;">Login</a> atau
        <a href="./register.php" style="color:#1a7f5a; font-weight:600;">daftar</a> supaya riwayat konsultasi kamu tersimpan.
      </div>
    <?php endif; ?>
  <?php endif; ?>

  <!-- ── Tombol Kembali ── -->
  <div class="d-flex justify-content-center mt-2">
    <a href="./konsultasi.php" class="btn-kembali">
      <i class="bi bi-arrow-left-circle"></i> Konsultasi Lagi
    </a>
  </div>

</div>

<script>
  // Animasi bar belief setelah load
  window.addEventListener('load', function () {
    var bar = document.getElementById('cfBar');
    if (bar) {
      setTimeout(function () {
        bar.style.width = '<?= $hasil_belief ?? 0 ?>%';
      }, 200);
    }
  });
</script>
</body>
</html>