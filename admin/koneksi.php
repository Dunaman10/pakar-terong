<?php
/**
 * Koneksi database.
 *
 * Mendukung:
 * 1. Environment Variables (Vercel / Cloud Hosting)
 * 2. config.local.php (Hosting cPanel / Override lokal pribadi)
 * 3. Default fallback lokal (Herd / XAMPP)
 */
require_once __DIR__ . '/sesi.php';

// Baca kredensial dari Environment Variables (Vercel / Docker) jika ada,
// jika tidak ada, gunakan default lokal
$cfg = [
  'host'  => getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? 'localhost'),
  'port'  => (int) (getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? 3306)),
  'user'  => getenv('DB_USERNAME') ?: (getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? 'root')),
  'pass'  => getenv('DB_PASSWORD') ?: (getenv('DB_PASS') ?: ($_ENV['DB_PASS'] ?? '')),
  'name'  => getenv('DB_DATABASE') ?: (getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? 'db_sp_terong')),
  'ssl'   => filter_var(getenv('DB_SSL') ?: ($_ENV['DB_SSL'] ?? false), FILTER_VALIDATE_BOOLEAN),
  'debug' => false,
];

// Jika ada file config.local.php di server lokal, timpa pengaturannya
if (is_file(__DIR__ . '/config.local.php')) {
  $cfg = array_merge($cfg, (array) require __DIR__ . '/config.local.php');
}

ini_set('display_errors', !empty($cfg['debug']) ? '1' : '0');
ini_set('log_errors', '1');

mysqli_report(MYSQLI_REPORT_OFF);

// Inisialisasi mysqli
$koneksi = mysqli_init();
if (!$koneksi) {
  error_log('mysqli_init gagal');
  http_response_code(500);
  die('Layanan sedang tidak tersedia. Silakan coba beberapa saat lagi.');
}

// Atur SSL jika aktif (wajib untuk cloud DB seperti Aiven)
if (!empty($cfg['ssl'])) {
  $ca = getenv('DB_SSL_CA') ?: ($_ENV['DB_SSL_CA'] ?? null);
  mysqli_ssl_set($koneksi, NULL, NULL, $ca ?: NULL, NULL, NULL);
  if (defined('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT')) {
    mysqli_options($koneksi, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, !empty($ca));
  }
}

$terhubung = @mysqli_real_connect(
  $koneksi,
  $cfg['host'],
  $cfg['user'],
  $cfg['pass'],
  $cfg['name'],
  (int) $cfg['port'],
  NULL,
  !empty($cfg['ssl']) ? MYSQLI_CLIENT_SSL : 0
);

if (!$terhubung) {
  error_log('Koneksi database gagal: ' . mysqli_connect_error());
  http_response_code(500);
  die('Layanan database sedang tidak tersedia. Silakan coba beberapa saat lagi.');
}

mysqli_set_charset($koneksi, 'utf8mb4');
unset($cfg, $terhubung);
