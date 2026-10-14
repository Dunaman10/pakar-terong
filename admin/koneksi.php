<?php
/**
 * Koneksi database.
 *
 * Lokal (XAMPP): jalan apa adanya dengan default di bawah.
 * Hosting: salin config.example.php -> config.local.php, lalu isi kredensial dari panel hosting.
 */
require_once __DIR__ . '/sesi.php';

$cfg = [
  'host'  => 'localhost',
  'user'  => 'root',
  'pass'  => 'dunaman',
  'name'  => 'db_sp_terong',
  'debug' => false, // true = tampilkan error PHP di layar (HANYA untuk lokal)
];
if (is_file(__DIR__ . '/config.local.php')) {
  $cfg = array_merge($cfg, (array) require __DIR__ . '/config.local.php');
}

// Pesan error PHP/SQL cukup masuk log server, jangan ditampilkan ke pengunjung
ini_set('display_errors', $cfg['debug'] ? '1' : '0');
ini_set('log_errors', '1');

mysqli_report(MYSQLI_REPORT_OFF);
$koneksi = @mysqli_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['name']);
if (!$koneksi) {
  error_log('Koneksi database gagal: ' . mysqli_connect_error());
  http_response_code(500);
  die('Layanan sedang tidak tersedia. Silakan coba beberapa saat lagi.');
}
mysqli_set_charset($koneksi, 'utf8mb4');
unset($cfg);
