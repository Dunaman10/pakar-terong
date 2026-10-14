<?php
/**
 * Helper sesi. Dipakai semua halaman (publik & admin) sebagai pengganti session_start().
 * Cookie sesi dibuat HttpOnly + SameSite=Lax, dan Secure otomatis kalau situs diakses lewat HTTPS.
 */
if (!function_exists('mulai_sesi')) {
  function mulai_sesi(): void
  {
    if (session_status() === PHP_SESSION_ACTIVE) {
      return;
    }
    if (getenv('VERCEL') || isset($_ENV['VERCEL'])) {
      @session_save_path('/tmp');
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
      || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_set_cookie_params([
      'lifetime' => 0,
      'path'     => '/',
      'secure'   => $https,
      'httponly' => true,
      'samesite' => 'Lax',
    ]);
    session_start();
  }
}

// Cadangan kalau ekstensi mbstring tidak aktif di hosting
if (!function_exists('mb_strlen')) {
  function mb_strlen($teks, $encoding = null): int
  {
    $n = preg_match_all('/./su', (string) $teks);
    return $n === false ? strlen((string) $teks) : $n;
  }
}
