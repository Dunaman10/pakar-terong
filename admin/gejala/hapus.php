<?php
$auth_ajax = true;
include "../auth.php";

$aksi = $_GET['aksi'] ?? '';
$id   = isset($_GET['data_hapus']) ? (int) $_GET['data_hapus'] : 0;

if ($aksi !== 'gejala' || $id <= 0) {
  echo "gagal";
  exit;
}

$stmt = $koneksi->prepare("DELETE FROM tb_gejala WHERE id = ?");
$stmt->bind_param("i", $id);
echo ($stmt->execute() && $stmt->affected_rows > 0) ? "sukses" : "gagal";
$stmt->close();
