<?php
include "../auth.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
  header("Location: ./bobot.php?msg=Parameter+tidak+valid.&type=error");
  exit;
}

// Hapus bobot berdasarkan id tb_bobot
$stmt = $koneksi->prepare("DELETE FROM tb_bobot WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
  $msg  = urlencode("Nilai belief berhasil dihapus.");
  $type = "success";
} else {
  $msg  = urlencode("Gagal menghapus nilai belief.");
  $type = "error";
}
$stmt->close();

header("Location: ./bobot.php?msg={$msg}&type={$type}");
exit;
