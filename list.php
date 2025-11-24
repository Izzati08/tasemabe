<?php
require_once __DIR__ . '/auth.php';
requireRole('admin');

$conn = mysqli_connect("localhost", "root", "", "koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

$result = mysqli_query($conn, "SELECT nomor_anggota, nama_lengkap, no_ktp, alamat FROM anggota ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Anggota Koperasi Tasemabe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
  <div class="card shadow-lg">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <img src="logo.png" alt="Logo Koperasi" style="height:40px; margin-right:10px;">
      <h4 class="mb-0">Daftar Anggota Koperasi Tasemabe</h4>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-striped table-hover">
        <thead class="table-success text-center">
          <tr>
            <th>Nomor Anggota</th>
            <th>Nama Lengkap</th>
            <th>No KTP</th>
            <th>Alamat</th>
            <th style="width:150px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= e($row['nomor_anggota']) ?></td>
                <td><?= e($row['nama_lengkap']) ?></td>
                <td><?= e($row['no_ktp']) ?></td>
                <td><?= e($row['alamat']) ?></td>
                <td class="text-center">
                  <a href="edit.php?nomor_anggota=<?= urlencode($row['nomor_anggota']) ?>" class="btn btn-warning btn-sm">Edit</a>
                  <a href="delete.php?nomor_anggota=<?= urlencode($row['nomor_anggota']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
              <tr>
                <td colspan="5" class="text-center text-muted">Belum ada data anggota</td>
              </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
  </div>
</div>
</body>
</html>
