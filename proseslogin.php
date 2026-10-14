<?php
include './admin/koneksi.php';
mulai_sesi();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ./login.php");
  exit;
}

$email = trim($_POST['email'] ?? '');
$pass  = (string) ($_POST['password'] ?? '');

if ($email === '' || $pass === '') {
  header("Location: ./login.php?pesan=1");
  exit;
}

$stmt = $koneksi->prepare('SELECT id, nama, password FROM tb_user WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->bind_result($user_id, $user_nama, $user_hash);
$ketemu = $stmt->fetch();
$stmt->close();

// Kalau email tidak ada, tetap verifikasi ke hash palsu supaya waktu responsnya sama
$hash_cek = $ketemu ? $user_hash : '$2y$10$iph7eKG4t.WV6Jt3ot11gO5o/AzEsxk2voqV6CiNupwyEQPSh4hzq';

if (password_verify($pass, $hash_cek) && $ketemu) {
  session_regenerate_id(true); // cegah session fixation
  $_SESSION['user_id']   = $user_id;
  $_SESSION['user_nama'] = $user_nama;
  header("Location: ./index.php");
  exit;
}

usleep(500000);
header("Location: ./login.php?pesan=1");
exit;
