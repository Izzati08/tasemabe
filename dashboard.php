<?php
require_once __DIR__ . '/auth.php'; // sudah ada session & koneksi

$username = $_SESSION['username'];
$role     = $_SESSION['role'];
$nomor    = $_SESSION['nomor_anggota'] ?? '';

// cek apakah anggota sudah isi data
$anggota = null;
if ($nomor) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM anggota WHERE nomor_anggota=?");
    mysqli_stmt_bind_param($stmt, "s", $nomor);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $anggota = mysqli_fetch_assoc($res);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Koperasi Tasemabe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- penting untuk layar HP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body { background-color:#f8f9fa; }
    .card { border-radius:12px; }
    .btn-menu { border-radius:10px; padding:12px; font-size:16px; }
    .navbar-brand { font-weight:600; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <span class="navbar-brand">Koperasi Tani Sejahtera Mandiri Banten Serang</span>
    <div class="d-flex">
      <span class="text-white me-3">👤 <?= e($username) ?> (<?= e($role) ?>)</span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="card shadow">
    <div class="card-body text-center">
      <h4 class="mb-3">Dashboard Anggota</h4>
      <p class="text-muted">Selamat datang di aplikasi Koperasi Tasemabe</p>

      <?php if (!$anggota): ?>
        <div class="alert alert-warning">
          ⚠️ Anda belum mengisi formulir pendaftaran anggota.
        </div>
        <a href="form.php" class="btn btn-success btn-menu w-100 mb-2">
          <i class="bi bi-pencil-square"></i> Isi Formulir Pendaftaran
        </a>
      <?php else: ?>
        <div class="alert alert-info">
          🧾 Nomor Anggota: <strong><?= e($anggota['nomor_anggota']) ?></strong>
        </div>
        <a href="edit.php" class="btn btn-primary btn-menu w-100 mb-2">
          <i class="bi bi-person-lines-fill"></i> Edit Profil
        </a>
        <a href="barcode.php?code=<?= urlencode($anggota['nomor_anggota']) ?>" class="btn btn-success btn-menu w-100 mb-2">
          <i class="bi bi-upc-scan"></i> Lihat Barcode
        </a>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
