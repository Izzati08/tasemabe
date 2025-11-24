<?php
require_once __DIR__ . '/auth.php';

// Validasi hanya admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID anggota tidak ditemukan.");
}

// Hapus data anggota berdasarkan ID
$stmt = mysqli_prepare($conn, "DELETE FROM anggota WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    // Jika berhasil, kembali ke daftar anggota
    header("Location: data_anggota.php?msg=deleted");
    exit;
} else {
    die("Gagal menghapus data: " . mysqli_error($conn));
}
