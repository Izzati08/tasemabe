<?php
$conn = mysqli_connect("localhost", "root", "", "koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

include "fungsi_nomor.php";

$tanggal = $_POST['tanggal_daftar'] ?? '';
$nama    = $_POST['nama_lengkap'] ?? '';
$ktp     = $_POST['no_ktp'] ?? '';
$alamat  = $_POST['alamat'] ?? '';

if ($tanggal === '' || $nama === '' || $ktp === '' || $alamat === '') {
    tampilError("Form belum lengkap. Mohon isi semua field.");
    exit;
}

// Validasi KTP harus 16 digit angka
if (!preg_match('/^\d{16}$/', $ktp)) {
    tampilError("No KTP harus 16 digit angka.");
    exit;
}

// Cek KTP sudah terdaftar
$stmt = mysqli_prepare($conn, "SELECT nomor_anggota FROM anggota WHERE no_ktp=?");
mysqli_stmt_bind_param($stmt, "s", $ktp);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    tampilError("No KTP sudah terdaftar dengan Nomor Anggota: " . htmlspecialchars($row['nomor_anggota']));
    exit;
}

// Generate nomor anggota
$nomor = generateNomorAnggota($tanggal, $conn);

// Cek nomor anggota unik
$stmt2 = mysqli_prepare($conn, "SELECT id FROM anggota WHERE nomor_anggota=?");
mysqli_stmt_bind_param($stmt2, "s", $nomor);
mysqli_stmt_execute($stmt2);
$res2 = mysqli_stmt_get_result($stmt2);
if (mysqli_num_rows($res2) > 0) {
    tampilError("Nomor Anggota $nomor sudah ada. Silakan coba lagi.");
    exit;
}

// Simpan data dengan prepared statement
$stmt3 = mysqli_prepare($conn, "INSERT INTO anggota (nomor_anggota, tanggal_daftar, nama_lengkap, no_ktp, alamat) VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt3, "sssss", $nomor, $tanggal, $nama, $ktp, $alamat);

if (mysqli_stmt_execute($stmt3)) {
    tampilSukses($nomor, $nama, $ktp, $alamat);
} else {
    tampilError("Gagal menyimpan data: " . mysqli_error($conn));
}

// ===== Tampilan Error =====
function tampilError($msg) {
    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
      <meta charset='UTF-8'>
      <title>Pendaftaran Ditolak</title>
      <meta name='viewport' content='width=device-width, initial-scale=1'>
      <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light d-flex align-items-center' style='min-height:100vh;'>
      <div class='container'>
        <div class='card shadow-lg mx-auto' style='max-width:500px; border-radius:15px;'>
          <div class='card-body text-center'>
            <div class='alert alert-danger'>
              <h4 class='alert-heading mb-2'>Pendaftaran Ditolak ❌</h4>
              <p class='mb-0'>" . $msg . "</p>
            </div>
            <a href='index.php' class='btn btn-primary btn-lg w-100 mt-3'>Kembali ke Form</a>
            <a href='dashboard.php' class='btn btn-secondary btn-lg w-100 mt-2'>Kembali ke Dashboard</a>
          </div>
        </div>
      </div>
    </body>
    </html>";
}

// ===== Tampilan Sukses =====
function tampilSukses($nomor, $nama, $ktp, $alamat) {
    echo "<!DOCTYPE html>
    <html lang='id'>
    <head>
      <meta charset='UTF-8'>
      <title>Pendaftaran Berhasil</title>
      <meta name='viewport' content='width=device-width, initial-scale=1'>
      <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light d-flex align-items-center' style='min-height:100vh;'>
      <div class='container'>
        <div class='card shadow-lg mx-auto' style='max-width:600px; border-radius:15px;'>
          <div class='card-header bg-success text-white text-center'>
            <h4 class='mb-0'>Pendaftaran Berhasil ✅</h4>
          </div>
          <div class='card-body'>
            <p><strong>Nomor Anggota:</strong> " . htmlspecialchars($nomor) . "</p>
            <p><strong>Nama Lengkap:</strong> " . htmlspecialchars($nama) . "</p>
            <p><strong>No KTP:</strong> " . htmlspecialchars($ktp) . "</p>
            <p><strong>Alamat:</strong> " . nl2br(htmlspecialchars($alamat)) . "</p>
            <div class='text-center my-3'>
              <img src='barcode.php?code=" . urlencode($nomor) . "' alt='Barcode' class='img-fluid'>
            </div>
            <a href='index.php' class='btn btn-success btn-lg w-100'>Tambah Anggota Baru</a>
            <a href='dashboard.php' class='btn btn-primary btn-lg w-100 mt-2'>Kembali ke Dashboard</a>
          </div>
        </div>
      </div>
    </body>
    </html>";
}
?>
