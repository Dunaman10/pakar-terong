<?php
include "../auth.php";

$id_penyakit = isset($_POST['id_penyakit']) ? trim($_POST['id_penyakit']) : '';
$id_gejala   = isset($_POST['id_gejala'])   ? $_POST['id_gejala'] : [];

// Validasi
if (empty($id_penyakit) || empty($id_gejala)) {
  header("Location: ./relasi.php?msg=Data+tidak+lengkap.&type=error");
  exit;
}

$berhasil  = 0;
$duplikat  = 0;
$gagal     = 0;

foreach ($id_gejala as $idg) {
  $idg = (int)$idg;

  // Cek apakah relasi sudah ada (hindari duplikat)
  $cek = $koneksi->prepare("SELECT id FROM tb_relasi WHERE id_penyakit = ? AND id_gejala = ?");
  $cek->bind_param("si", $id_penyakit, $idg);
  $cek->execute();
  $cek->store_result();

  if ($cek->num_rows > 0) {
    $duplikat++;
    $cek->close();
    continue;
  }
  $cek->close();

  // Insert relasi baru
  $stmt = $koneksi->prepare("INSERT INTO tb_relasi (id_penyakit, id_gejala) VALUES (?, ?)");
  $stmt->bind_param("si", $id_penyakit, $idg);
  if ($stmt->execute()) {
    $berhasil++;
  } else {
    $gagal++;
  }
  $stmt->close();
}

// Susun pesan hasil
if ($berhasil > 0 && $duplikat === 0) {
  $msg  = "{$berhasil} relasi berhasil disimpan.";
  $type = "success";
} elseif ($berhasil > 0 && $duplikat > 0) {
  $msg  = "{$berhasil} relasi disimpan, {$duplikat} relasi sudah ada (dilewati).";
  $type = "success";
} elseif ($berhasil === 0 && $duplikat > 0) {
  $msg  = "Semua relasi yang dipilih sudah ada sebelumnya.";
  $type = "error";
} else {
  $msg  = "Gagal menyimpan relasi.";
  $type = "error";
}

header("Location: ./relasi.php?msg=" . urlencode($msg) . "&type={$type}");
exit;
