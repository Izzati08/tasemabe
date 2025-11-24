<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_anggota.xls");

$conn = mysqli_connect("localhost", "root", "", "koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

$bulan = $_GET['bulan'] ?? '';
$tahun = $_GET['tahun'] ?? '';

$where = '';
if ($bulan !== '' && $tahun !== '') {
    $where = "WHERE MONTH(tanggal_daftar) = '$bulan' AND YEAR(tanggal_daftar) = '$tahun'";
}

$query = "SELECT * FROM anggota $where ORDER BY tanggal_daftar ASC";
$result = mysqli_query($conn, $query);

echo "<table border='1'>";
echo "<tr>
        <th>Nomor Anggota</th>
        <th>Nama Lengkap</th>
        <th>No KTP</th>
        <th>Alamat</th>
        <th>Tanggal Daftar</th>
      </tr>";

while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>".$row['nomor_anggota']."</td>
            <td>".$row['nama_lengkap']."</td>
            <td>".$row['no_ktp']."</td>
            <td>".$row['alamat']."</td>
            <td>".date("d-m-Y", strtotime($row['tanggal_daftar']))."</td>
          </tr>";
}
echo "</table>";
?>
