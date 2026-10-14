<?php
include "../auth.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: ./penyakit.php");
  exit;
}

$id        = (int) ($_POST['kdpenyakit'] ?? 0); // field ini membawa id penyakit
$penyakit  = trim($_POST['edit_penyakit'] ?? '');
$definisi  = trim($_POST['edit_definisi'] ?? '');
$solusi    = trim($_POST['edit_solusi'] ?? '');
$sukses    = false;

if ($id > 0 && $penyakit !== '' && mb_strlen($penyakit) <= 100) {
  $stmt = $koneksi->prepare("UPDATE tb_penyakit SET nama_penyakit = ?, definisi = ?, solusi = ? WHERE id = ?");
  $stmt->bind_param("sssi", $penyakit, $definisi, $solusi, $id);
  $sukses = $stmt->execute();
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="description">
  <meta content="" name="keywords">
  <title>Edit Data Penyakit</title>
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>

<body>
  <?php if ($sukses): ?>
    <table style='margin-top:150px;' align='center'><tr><td>
      <div style='width:500px; height:50px auto; border:1px solid #CCC; font-family:Poppins; padding:3px 3px 3px 3px;'>
        <center>terima kasih, data berhasil diubah</center><br>
        <center><a href='./penyakit.php' class='btn btn-primary btn-sm'>OK</a></center>
      </div>
    </td></tr></table>
  <?php else: ?>
    <center><font color='#ff0000'><strong>Maaf gagal mengupdate data</strong></font></center><br>
    <center><a href='./penyakit.php' class='btn btn-danger btn-sm'>Kembali</a></center>
  <?php endif; ?>
</body>

</html>
