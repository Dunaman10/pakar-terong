<?php
include './admin/koneksi.php';
mulai_sesi();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ./register.php");
  exit;
}

$nama          = trim($_POST['nama'] ?? '');
$umur          = trim($_POST['umur'] ?? '');
$jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
$alamat        = trim($_POST['alamat'] ?? '');
$email         = trim($_POST['email'] ?? '');
$no_hp         = trim($_POST['no_hp'] ?? '');
$pass          = (string) ($_POST['password'] ?? '');
$pass2         = (string) ($_POST['konfirmasi_password'] ?? '');

function balik_register(string $pesan): void
{
  header("Location: ./register.php?pesan=" . $pesan);
  exit;
}

// ── Validasi dasar ────────────────────────────────────────────────
if ($nama === '' || $umur === '' || $jenis_kelamin === '' || $alamat === '' || $email === '' || $pass === '' || $pass2 === '') {
  balik_register('kosong');
}
if (!in_array($jenis_kelamin, ['Laki-laki', 'Perempuan'], true)) {
  balik_register('kosong');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
  balik_register('email_invalid');
}
if (mb_strlen($nama) > 100 || mb_strlen($alamat) > 1000 || mb_strlen($no_hp) > 20 || !ctype_digit($umur) || (int) $umur < 1 || (int) $umur > 120) {
  balik_register('data_invalid');
}
if ($no_hp !== '' && !preg_match('/^[0-9+\-\s]+$/', $no_hp)) {
  balik_register('data_invalid');
}
if (strlen($pass) < 8) {
  balik_register('password_pendek');
}
if ($pass !== $pass2) {
  balik_register('password_beda');
}

// ── Cek email sudah dipakai atau belum ────────────────────────────
$cek = $koneksi->prepare('SELECT id FROM tb_user WHERE email = ? LIMIT 1');
$cek->bind_param('s', $email);
$cek->execute();
$cek->store_result();
$sudah_ada = $cek->num_rows > 0;
$cek->close();
if ($sudah_ada) {
  balik_register('email_terdaftar');
}

// ── Simpan user baru (password di-hash, bukan plain text) ────────
$umur_int  = (int) $umur;
$pass_hash = password_hash($pass, PASSWORD_DEFAULT);

$stmt = $koneksi->prepare(
  'INSERT INTO tb_user (nama, umur, jenis_kelamin, alamat, email, password, no_hp) VALUES (?, ?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param('sisssss', $nama, $umur_int, $jenis_kelamin, $alamat, $email, $pass_hash, $no_hp);

if ($stmt->execute()) {
  $id_baru = $stmt->insert_id;
  $stmt->close();
  session_regenerate_id(true);
  // Langsung login-kan user setelah daftar
  $_SESSION['user_id']   = $id_baru;
  $_SESSION['user_nama'] = $nama;
  header("Location: ./index.php");
  exit;
}

$errno = $stmt->errno;
$stmt->close();
balik_register($errno === 1062 ? 'email_terdaftar' : 'gagal'); // 1062 = duplikat email (race condition)
