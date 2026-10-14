<?php
/**
 * Vercel Serverless Entrypoint & Front-Controller Router.
 *
 * Mengarahkan setiap request HTTP di Vercel ke file PHP yang sesuai
 * di root project atau subfolder admin.
 */

// Pastikan sesi PHP menggunakan direktori /tmp yang writable di environment serverless Vercel
if (getenv('VERCEL') || isset($_ENV['VERCEL'])) {
    if (ini_get('session.save_path') === '' || !is_writable(session_save_path())) {
        @session_save_path('/tmp');
    }
}

$baseDir = dirname(__DIR__);
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);

// Normalisasi path
$path = trim($path, '/');

// Mapping default
if ($path === '' || $path === 'index.php') {
    $relativePath = 'index.php';
} elseif ($path === 'admin' || $path === 'admin/' || $path === 'admin/index.php') {
    $relativePath = 'admin/index.php';
} else {
    $relativePath = $path;
}

// Cari path file target
$targetFile = $baseDir . '/' . $relativePath;

// Jika tidak ada ekstensi .php dan file tersebut tidak ada, coba tambahkan .php
if (!file_exists($targetFile) && file_exists($targetFile . '.php')) {
    $targetFile .= '.php';
    $relativePath .= '.php';
}

// Cek jika menunjuk ke sebuah folder (misal: admin/bobot/)
if (is_dir($targetFile)) {
    if (file_exists($targetFile . '/index.php')) {
        $targetFile .= '/index.php';
        $relativePath .= '/index.php';
    }
}

// Validasi realpath untuk mencegah directory traversal
$realTarget = realpath($targetFile);
$realBase   = realpath($baseDir);

if (
    !$realTarget ||
    !is_file($realTarget) ||
    strpos($realTarget, $realBase) !== 0 ||
    pathinfo($realTarget, PATHINFO_EXTENSION) !== 'php'
) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body><h1>404 Not Found</h1><p>Halaman tidak ditemukan.</p></body></html>';
    exit;
}

// Sesuaikan server variables agar PHP_SELF dan SCRIPT_NAME konsisten
$_SERVER['SCRIPT_FILENAME'] = $realTarget;
$_SERVER['SCRIPT_NAME']     = '/' . ltrim($relativePath, '/');
$_SERVER['PHP_SELF']        = '/' . ltrim($relativePath, '/');

// Pindahkan working directory ke folder file target
// Ini penting agar include relatif (seperti "./admin/koneksi.php" atau "../koneksi.php") bekerja persis seperti di Apache/Herd
chdir(dirname($realTarget));

// Eksekusi file PHP target
require $realTarget;
