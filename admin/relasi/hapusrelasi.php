<?php
$auth_ajax = true;
include "../auth.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
  echo "gagal";
  exit;
}

// Hapus relasi berdasarkan id
$stmt = $koneksi->prepare("DELETE FROM tb_relasi WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
  echo "sukses";
} else {
  echo "gagal";
}
$stmt->close();
