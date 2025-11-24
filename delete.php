<?php
require_once __DIR__ . '/auth.php';
requireRole('admin');

$conn = mysqli_connect("localhost", "root", "", "koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

$nomor = $_GET['nomor_anggota'] ?? '';
if ($nomor == '') {
  die("<div class='alert alert-danger text-center'>Nomor anggota tidak ditemukan.</div>");
}

$stmt = mysqli_prepare($conn, "DELETE FROM anggota WHERE nomor_anggota=?");
mysqli_stmt_bind_param($stmt, "s", $nomor);

if (mysqli_stmt_execute($stmt)) {
  echo "<!DOCTYPE html><html lang='id'><head>
    <meta charset='UTF-8'><title>Data Dihapus</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
  </head><body class='bg-light d-flex align-items-center' style='min-height:100vh;'>
    <div class='container'>
      <div class='card shadow-lg mx-auto' style='max-width:500px; border-radius:15px;'>
        <div class='card-body text-center'>
          <div class='alert alert-danger'>
            <h4 class='alert-heading mb-2'>Data Anggota Dihapus ❌</h4>
            <p>Data anggota dengan Nomor <strong>".e($nomor)."</strong> berhasil dihapus.</p>
          </div>
          <a href='list.php' class='btn btn-primary btn-lg w-100 mt-3'>Kembali ke Daftar Anggota</a>
          <a href='dashboard.php' class='btn btn-secondary btn-lg w-100 mt-2'>Kembali ke Dashboard</a>
        </div>
      </div>
    </div></body></html>";
} else {
  echo "<!DOCTYPE html><html lang='id'><head>
    <meta charset='UTF-8'><title>Hapus Gagal</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
  </head><body class='bg-light d-flex align-items-center' style='min-height:100vh;'>
    <div class='container'>
      <div class='card shadow-lg mx-auto' style='max-width:500px; border-radius:15px;'>
        <div class='card-body text-center'>
          <div class='alert alert-danger'>
            <h4 class='alert-heading mb-2'>Hapus Gagal ❌</h4>
            <p>Terjadi kesalahan.</p>
          </div>
          <a href='list.php' class='btn btn-primary btn-lg w-100 mt-3'>Kembali ke Daftar Anggota</a>
        </div>
      </div>
    </div></body></html>";
}
