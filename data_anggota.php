<?php
require_once __DIR__ . '/auth.php';

// Validasi hanya admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }

// Ambil data anggota
$result = mysqli_query($conn, "SELECT id, nomor_anggota, nama_lengkap, alamat, no_hp FROM anggota ORDER BY nomor_anggota ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Anggota</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">📋 Data Anggota</span>
    <div class="d-flex">
      <a href="dashboard_admin.php" class="btn btn-outline-light btn-sm me-2">⬅ Dashboard Admin</a>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Konten -->
<div class="container mt-4">
  <div class="card shadow">
    <div class="card-body">
      <h4 class="mb-3">Daftar Anggota Koperasi</h4>

      <!-- Notifikasi -->
      <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-success">Data anggota berhasil dihapus.</div>
      <?php endif; ?>
      <?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
        <div class="alert alert-success">Anggota baru berhasil ditambahkan.</div>
      <?php endif; ?>
      <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
        <div class="alert alert-success">Data anggota berhasil diperbarui.</div>
      <?php endif; ?>

      <div class="mb-3">
        <a href="tambah_anggota.php" class="btn btn-success">
          <i class="bi bi-person-plus-fill"></i> Tambah Anggota
        </a>
      </div>

      <table class="table table-striped table-bordered table-hover">
        <thead class="table-dark">
          <tr>
            <th>No Anggota</th>
            <th>Nama Lengkap</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= e($row['nomor_anggota']) ?></td>
                <td><?= e($row['nama_lengkap']) ?></td>
                <td><?= e($row['alamat']) ?></td>
                <td><?= e($row['no_hp']) ?></td>
                <td>
                  <a href="edit_anggota.php?id=<?= e($row['id']) ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i> Edit
                  </a>
                  <a href="hapus_anggota.php?id=<?= e($row['id']) ?>" 
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('Yakin ingin menghapus anggota ini?')">
                    <i class="bi bi-trash-fill"></i> Hapus
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted">Belum ada data anggota.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="text-center mt-4 mb-2 text-muted">
  <small>&copy; <?= date('Y') ?> Koperasi Tasemabe. Semua hak dilindungi.</small>
</footer>
</body>
</html>
