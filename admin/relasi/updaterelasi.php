<?php
include "../auth.php";

$id          = isset($_POST['id'])          ? (int)trim($_POST['id'])          : 0;
$id_penyakit = isset($_POST['id_penyakit']) ? trim($_POST['id_penyakit'])       : '';
$id_gejala   = isset($_POST['id_gejala'])   ? (int)trim($_POST['id_gejala'])    : 0;

// Validasi
if ($id <= 0 || empty($id_penyakit) || $id_gejala <= 0) {
  header("Location: ./relasi.php?msg=Data+tidak+lengkap.&type=error");
  exit;
}

// Cek duplikat — pastikan kombinasi baru tidak bentrok dengan data lain (kecuali dirinya sendiri)
$cek = $koneksi->prepare("SELECT id FROM tb_relasi WHERE id_penyakit = ? AND id_gejala = ? AND id != ?");
$cek->bind_param("sii", $id_penyakit, $id_gejala, $id);
$cek->execute();
$cek->store_result();

if ($cek->num_rows > 0) {
  $cek->close();
  header("Location: ./relasi.php?msg=Relasi+tersebut+sudah+ada.&type=error");
  exit;
}
$cek->close();

// Update
$stmt = $koneksi->prepare("UPDATE tb_relasi SET id_penyakit = ?, id_gejala = ? WHERE id = ?");
$stmt->bind_param("sii", $id_penyakit, $id_gejala, $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
  $msg  = urlencode("Relasi berhasil diperbarui.");
  $type = "success";
} else {
  $msg  = urlencode("Tidak ada perubahan atau gagal memperbarui relasi.");
  $type = "error";
}
$stmt->close();

header("Location: ./relasi.php?msg={$msg}&type={$type}");
exit;
