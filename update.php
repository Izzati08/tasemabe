<?php
require_once __DIR__ . '/auth.php';

$conn = mysqli_connect("localhost", "root", "", "koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

$nomor   = $_POST['nomor_anggota'] ?? '';
$nama    = $_POST['nama_lengkap'] ?? '';
$alamat  = $_POST['alamat'] ?? '';
$tanggal = $_POST['tanggal_daftar'] ?? '';

if ($nomor === '' || $nama === '' || $alamat === '' || $tanggal === '') {
  die("<div class='alert alert-danger text-center'>Form belum lengkap.</div>");
}

// Batasi: anggota hanya boleh update miliknya
if ($_SESSION['role'] === 'anggota' && $_SESSION['nomor_anggota'] !== $nomor) {
  http_response_code(403);
  die("<div class='alert alert-danger text-center'>Anda hanya boleh memperbarui data Anda sendiri.</div>");
}

$stmt = mysqli_prepare($conn, "UPDATE anggota SET nama_lengkap=?, alamat=?, tanggal_daftar=? WHERE nomor_anggota=?");
mysqli_stmt_bind_param($stmt, "ssss", $nama, $alamat, $tanggal, $nomor);

if (mysqli_stmt_execute($stmt)) {
  echo "<!DOCTYPE html><html lang='id'><head>
    <meta charset='UTF-8'><title>Update Berhasil</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
  </head><body class='bg-light d-flex align-items-center' style='min-height:100vh;'>
    <div class='container'>
      <div class='card shadow-lg mx-auto' style='max-width:500px; border-radius:15px;'>
        <div class='card-body text-center'>
          <div class='alert alert-success'>
            <h4 class='alert-heading mb-2'>Update Berhasil ✅</h4>
            <p>Data anggota dengan Nomor <strong>".e($nomor)."</strong> berhasil diperbarui.</p>
          </div>
          <a href='dashboard.php' class='btn btn-primary btn-lg w-100 mt-3'>Kembali ke Dashboard</a>
        </div>
      </div>
    </div></body></html>";
} else {
  echo "<!DOCTYPE html><html lang='id'><head>
    <meta charset='UTF-8'><title>Update Gagal</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
  </head><body class='bg-light d-flex align-items-center' style='min-height:100vh;'>
    <div class='container'>
      <div class='card shadow-lg mx-auto' style='max-width:500px; border-radius:15px;'>
        <div class='card-body text-center'>
          <div class='alert alert-danger'>
            <h4 class='alert-heading mb-2'>Update Gagal ❌</h4>
            <p>Terjadi kesalahan.</p>
          </div>
          <a href='dashboard.php' class='btn btn-primary btn-lg w-100 mt-3'>Kembali ke Dashboard</a>
        </div>
      </div>
    </div></body></html>";
}
