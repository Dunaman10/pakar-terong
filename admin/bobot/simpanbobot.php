<?php
include "../auth.php";

$id_penyakit = isset($_POST['id_penyakit']) ? trim($_POST['id_penyakit']) : '';
$id_gejala   = isset($_POST['id_gejala'])   ? $_POST['id_gejala']         : [];
$belief_arr  = isset($_POST['belief'])       ? $_POST['belief']            : [];
$id_bobot    = isset($_POST['id_bobot'])     ? $_POST['id_bobot']          : [];

// Validasi dasar
if (empty($id_penyakit) || empty($id_gejala)) {
  header("Location: ./bobot.php?msg=Data+tidak+lengkap.&type=error");
  exit;
}

$berhasil = 0;
$gagal    = 0;

for ($i = 0; $i < count($id_gejala); $i++) {
  $idg        = (int)$id_gejala[$i];
  $belief_val = isset($belief_arr[$i]) ? trim($belief_arr[$i]) : '';
  $id_b       = isset($id_bobot[$i])   ? trim($id_bobot[$i])   : '';

  // Kalau field belief dikosongkan, skip (tidak update)
  if ($belief_val === '') continue;

  // Validasi range belief
  $belief_num = (float)$belief_val;
  if ($belief_num < 0 || $belief_num > 1) {
    $gagal++;
    continue;
  }

  if (!empty($id_b)) {
    // Sudah ada bobot sebelumnya → UPDATE
    $stmt = $koneksi->prepare("UPDATE tb_bobot SET belief = ? WHERE id = ?");
    $stmt->bind_param("di", $belief_num, $id_b);
  } else {
    // Belum ada → INSERT
    $stmt = $koneksi->prepare("INSERT INTO tb_bobot (id_penyakit, id_gejala, belief) VALUES (?, ?, ?)");
    $stmt->bind_param("sid", $id_penyakit, $idg, $belief_num);
  }

  if ($stmt->execute()) {
    $berhasil++;
  } else {
    $gagal++;
  }
  $stmt->close();
}

// Susun pesan
if ($berhasil > 0 && $gagal === 0) {
  $msg  = "{$berhasil} bobot berhasil disimpan.";
  $type = "success";
} elseif ($berhasil > 0 && $gagal > 0) {
  $msg  = "{$berhasil} bobot disimpan, {$gagal} gagal (nilai tidak valid).";
  $type = "success";
} elseif ($berhasil === 0 && $gagal === 0) {
  $msg  = "Tidak ada perubahan yang disimpan.";
  $type = "error";
} else {
  $msg  = "Gagal menyimpan bobot. Periksa nilai belief (harus 0-1).";
  $type = "error";
}

header("Location: ./bobot.php?msg=" . urlencode($msg) . "&type={$type}");
exit;
