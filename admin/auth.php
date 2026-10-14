<?php
/**
 * Penjaga halaman admin. Taruh di baris paling atas setiap halaman admin:
 *   include "../auth.php";
 * Untuk endpoint AJAX (hapus), set $auth_ajax = true; sebelum include.
 */
require_once __DIR__ . '/koneksi.php';
mulai_sesi();

if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login') {
  if (!empty($auth_ajax)) {
    http_response_code(403);
    echo 'gagal';
    exit;
  }
  // Hitung path relatif ke admin/login.php dari halaman yang sedang dibuka
  $script = str_replace('\\', '/', (string) realpath($_SERVER['SCRIPT_FILENAME'] ?? ''));
  $base   = str_replace('\\', '/', (string) realpath(__DIR__));
  $rel    = ltrim(substr($script, strlen($base)), '/');
  header('Location: ' . str_repeat('../', substr_count($rel, '/')) . 'login.php');
  exit;
}

// Halaman admin jangan di-cache (supaya tombol Back setelah logout tidak menampilkan isi admin)
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
