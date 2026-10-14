<?php
include "../auth.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ./penyakit.php");
  exit;
}

$kdpenyakit    = trim($_POST['kdpenyakit'] ?? '');
$nama_penyakit = trim($_POST['nama_penyakit'] ?? '');
$definisi      = trim($_POST['definisi'] ?? '');
$solusi        = trim($_POST['solusi'] ?? '');
$status        = 'gagal';

if ($kdpenyakit === '' || $nama_penyakit === '' || mb_strlen($kdpenyakit) > 3 || mb_strlen($nama_penyakit) > 100) {
  $status = 'invalid';
} else {
  // cek keberadaan data
  $cek = $koneksi->prepare("SELECT id FROM tb_penyakit WHERE kdpenyakit = ?");
  $cek->bind_param("s", $kdpenyakit);
  $cek->execute();
  $cek->store_result();
  $ada = $cek->num_rows > 0;
  $cek->close();

  if ($ada) {
    $status = 'duplikat';
  } else {
    $ins = $koneksi->prepare("INSERT INTO tb_penyakit (kdpenyakit, nama_penyakit, definisi, solusi) VALUES (?, ?, ?, ?)");
    $ins->bind_param("ssss", $kdpenyakit, $nama_penyakit, $definisi, $solusi);
    $status = $ins->execute() ? 'ok' : 'gagal';
    $ins->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="description">
  <meta content="" name="keywords">
  <title>Simpan Data Penyakit</title>
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body>
  <?php if ($status === 'ok'): ?>
    <table style='margin-top:150px;' align='center'><tr><td>
      <div style='width:500px; height:50px auto; border:1px solid #CCC; font-family:Poppins; padding:3px 3px 3px 3px;'>
        <center>terima kasih, data berhasil disimpan</center><br>
        <center><a href='./penyakit.php' class='btn btn-primary btn-sm'>OK</a></center>
      </div>
    </td></tr></table>
  <?php elseif ($status === 'duplikat'): ?>
    <table style='margin-top:150px;' align='center'><tr><td>
      <div style='width:500px; height:50px auto; border:1px solid #CCC; color:#F90; padding:3px 3px 3px 3px;'>
        <center><font>Maaf kode penyakit <?= htmlspecialchars($kdpenyakit) ?> <strong>Telah ada di database, Masukkan Kode penyakit yang lain!</strong></font></center><br>
        <center><a href='./penyakit.php' class='btn btn-danger btn-sm'>Kembali</a></center>
      </div>
    </td></tr></table>
  <?php elseif ($status === 'invalid'): ?>
    <center><font color='#ff0000'><strong>Kode dan nama penyakit wajib diisi (kode maksimal 3 karakter, nama maksimal 100 karakter).</strong></font></center><br>
    <center><a href='./penyakit.php' class='btn btn-danger btn-sm'>Kembali</a></center>
  <?php else: ?>
    <center><font color='#ff0000'><strong>Maaf Penyimpanan Gagal</strong></font></center><br>
    <center><a href='./penyakit.php' class='btn btn-danger btn-sm'>Kembali</a></center>
  <?php endif; ?>
</body>

</html>
