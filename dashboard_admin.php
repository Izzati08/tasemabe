<?php
require_once __DIR__ . '/auth.php';

// Validasi hanya admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

// Statistik
$totalAnggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS jml FROM anggota"))['jml'];

// Backup terakhir
$backupDir = __DIR__ . "/backup/";
$lastBackup = '';
if (is_dir($backupDir)) {
    $files = glob($backupDir . "*.sql");
    if ($files) {
        $lastBackup = date("d-m-Y H:i", filemtime(max($files)));
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- penting untuk mobile -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark sticky-top">
  <div class="container-fluid">
    <span class="navbar-brand">📊 Dashboard Admin</span>
    <div class="d-flex">
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <!-- Ringkasan Statistik -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-6">
      <div class="card text-center shadow-sm h-100">
        <div class="card-body">
          <h6 class="text-muted">Total Anggota</h6>
          <p class="display-6 text-primary mb-0"><?= $totalAnggota ?></p>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6">
      <div class="card text-center shadow-sm h-100">
        <div class="card-body">
          <h6 class="text-muted">Backup Terakhir</h6>
          <p class="fw-bold mb-0"><?= $lastBackup ?: 'Belum ada backup' ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Menu Utama -->
  <div class="row g-3">
    <!-- Data Anggota -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card shadow-sm h-100 text-center">
        <div class="card-body">
          <i class="bi bi-people-fill display-5 text-primary"></i>
          <h6 class="mt-2">Data Anggota</h6>
          <p class="small text-muted">Kelola data anggota koperasi</p>
          <a href="data_anggota.php" class="btn btn-primary btn-sm w-100">Buka</a>
        </div>
      </div>
    </div>

    <!-- Laporan -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card shadow-sm h-100 text-center">
        <div class="card-body">
          <i class="bi bi-file-earmark-text-fill display-5 text-success"></i>
          <h6 class="mt-2">Laporan</h6>
          <p class="small text-muted">Lihat dan cetak laporan anggota</p>
          <a href="laporan.php" class="btn btn-success btn-sm w-100">Buka</a>
        </div>
      </div>
    </div>

    <!-- Pengaturan -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card shadow-sm h-100 text-center">
        <div class="card-body">
          <i class="bi bi-gear-fill display-5 text-warning"></i>
          <h6 class="mt-2">Pengaturan</h6>
          <p class="small text-muted">Atur nama koperasi, alamat, logo</p>
          <a href="pengaturan.php" class="btn btn-warning btn-sm w-100">Buka</a>
        </div>
      </div>
    </div>

    <!-- Backup & Restore -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card shadow-sm h-100 text-center">
        <div class="card-body">
          <i class="bi bi-database-fill-gear display-5 text-danger"></i>
          <h6 class="mt-2">Backup & Restore</h6>
          <p class="small text-muted">Simpan atau pulihkan database</p>
          <div class="d-grid gap-2">
            <a href="backup.php" class="btn btn-danger btn-sm">Backup</a>
            <a href="restore.php" class="btn btn-secondary btn-sm">Restore</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="text-center mt-4 mb-2 text-muted small">
  &copy; <?= date('Y') ?> Koperasi Tasemabe. Semua hak dilindungi.
</footer>
</body>
</html>
