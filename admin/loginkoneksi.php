<?php
include './koneksi.php';
mulai_sesi();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ./login.php');
  exit;
}

$username = trim($_POST['username'] ?? '');
$password = (string) ($_POST['password'] ?? '');

if ($username === '' || $password === '') {
  header('Location: ./login.php?pesan');
  exit;
}

// Prepared statement: input tidak pernah disambung ke string SQL
$stmt = $koneksi->prepare('SELECT id, username, password FROM tb_admin WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($admin_id, $admin_user, $admin_hash);
$ketemu = $stmt->fetch();
$stmt->close();

// Kalau username tidak ada, tetap verifikasi ke hash palsu supaya waktu responsnya sama
$hash_cek = $ketemu ? $admin_hash : '$2y$10$iph7eKG4t.WV6Jt3ot11gO5o/AzEsxk2voqV6CiNupwyEQPSh4hzq';

if (password_verify($password, $hash_cek) && $ketemu) {
  session_regenerate_id(true); // cegah session fixation
  $_SESSION['status']   = 'login';
  $_SESSION['username'] = $admin_user;
  $_SESSION['admin_id'] = $admin_id;
  header('Location: ./index.php');
  exit;
}

usleep(500000); // perlambat tebak-tebakan password
header('Location: ./login.php?pesan');
exit;
