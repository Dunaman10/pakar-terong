<?php
require_once __DIR__ . '/admin/sesi.php'; mulai_sesi();
unset($_SESSION['user_id']);
unset($_SESSION['user_nama']);
header("Location: ./index.php");
exit;
