<?php
require_once __DIR__ . '/auth.php';

// Validasi hanya admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

// Ambil data anggota
$result = mysqli_query($conn, "SELECT nomor_anggota, nama_lengkap, alamat, no_hp FROM anggota ORDER BY nomor_anggota ASC");

// Atur header agar output jadi file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_anggota.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Buat tabel HTML (akan dibaca Excel)
echo "<table border='1'>";
echo "<tr style='background:#f2f2f2;'>
        <th>No Anggota</th>
        <th>Nama Lengkap</th>
        <th>Alamat</th>
        <th>No HP</th>
      </tr>";

while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>".htmlspecialchars($row['nomor_anggota'])."</td>
            <td>".htmlspecialchars($row['nama_lengkap'])."</td>
            <td>".htmlspecialchars($row['alamat'])."</td>
            <td>".htmlspecialchars($row['no_hp'])."</td>
          </tr>";
}

echo "</table>";
?>
