<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

$conn = mysqli_connect("localhost","root","","koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }

$error = '';
$success = '';
$nomor = $_SESSION['nomor_anggota'] ?? '';

if ($nomor === '' || $nomor === null) {
  $error = "Nomor anggota belum ada. Silakan isi formulir pendaftaran terlebih dahulu.";
} else {
  $stmt = mysqli_prepare($conn, "SELECT * FROM anggota WHERE nomor_anggota=?");
  mysqli_stmt_bind_param($stmt, "s", $nomor);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  $anggota = mysqli_fetch_assoc($res);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = $_POST['nama_lengkap'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $hp     = $_POST['no_hp'] ?? '';

    if ($nama === '' || $alamat === '' || $hp === '') {
      $error = "Form belum lengkap.";
    } else {
      $stmt2 = mysqli_prepare($conn, "UPDATE anggota SET nama_lengkap=?, alamat=?, no_hp=? WHERE nomor_anggota=?");
      mysqli_stmt_bind_param($stmt2, "ssss", $nama, $alamat, $hp, $nomor);
      if (mysqli_stmt_execute($stmt2)) {
        $success = "Data berhasil diperbarui.";
        $anggota['nama_lengkap'] = $nama;
        $anggota['alamat'] = $alamat;
        $anggota['no_hp'] = $hp;
      } else {
        $error = "Terjadi kesalahan saat menyimpan data.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Profil Anggota</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
<div class="container">
  <div class="card shadow-lg mx-auto" style="max-width:500px; border-radius:15px;">
    <div class="card-header bg-primary text-white text-center"><h4>Edit Profil Anggota</h4></div>
    <div class="card-body">
      <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
      <?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
      <?php if (!empty($anggota)): ?>
      <form method="POST">
        <div class="mb-3"><label class="form-label">Nomor Anggota</label><input type="text" class="form-control" value="<?= e($anggota['nomor_anggota']) ?>" disabled></div>
        <div class="mb-3"><label class="form-label">Nama Lengkap</label><input type="text" name="nama_lengkap" class="form-control" value="<?= e($anggota['nama_lengkap']) ?>" required></div>
        <div class="mb-3"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" required><?= e($anggota['alamat']) ?></textarea></div>
        <div class="mb-3"><label class="form-label">Nomor HP</label><input type="text" name="no_hp" class="form-control" value="<?= e($anggota['no_hp']) ?>" required></div>
        <button type="submit" class="btn btn-success w-100">Simpan Perubahan</button>
        <a href="dashboard.php" class="btn btn-link w-100 mt-2">Kembali ke Dashboard</a>
      </form>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
