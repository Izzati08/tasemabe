<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/vendor/autoload.php'; // gunakan autoload dari Composer

use Dompdf\Dompdf;

// Validasi hanya admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$conn = mysqli_connect("localhost","root","","koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }

// Ambil data anggota
$result = mysqli_query($conn, "SELECT nomor_anggota, nama_lengkap, alamat, no_hp FROM anggota ORDER BY nomor_anggota ASC");

// Buat HTML laporan
$html = '
<h2 style="text-align:center;">Laporan Anggota Koperasi Tasemabe</h2>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
<thead>
<tr style="background:#f2f2f2;">
  <th>No Anggota</th>
  <th>Nama Lengkap</th>
  <th>Alamat</th>
  <th>No HP</th>
</tr>
</thead>
<tbody>';

while($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>
        <td>'.e($row['nomor_anggota']).'</td>
        <td>'.e($row['nama_lengkap']).'</td>
        <td>'.e($row['alamat']).'</td>
        <td>'.e($row['no_hp']).'</td>
    </tr>';
}

$html .= '</tbody></table>
<p style="text-align:right; margin-top:20px;">Dicetak pada: '.date("d-m-Y H:i").'</p>';

// Inisialisasi Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Atur ukuran kertas dan orientasi
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output ke browser (Attachment=false agar langsung tampil, bukan download otomatis)
$dompdf->stream("laporan_anggota.pdf", array("Attachment" => false));
exit;
