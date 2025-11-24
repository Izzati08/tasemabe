<?php
$conn = mysqli_connect("localhost","root","","koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

$result = mysqli_query($conn, "SELECT * FROM pengaturan LIMIT 1");
$pengaturan = mysqli_fetch_assoc($result);

$nama_koperasi = $pengaturan['nama_koperasi'] ?? 'Koperasi Tasemabe';
$logo          = $pengaturan['logo'] ?? '';
?>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <div class="d-flex align-items-center">
      <?php if (!empty($logo)): ?>
        <img src="<?= htmlspecialchars($logo) ?>" alt="Logo" style="height:40px;" class="me-2">
      <?php endif; ?>
      <span class="navbar-brand mb-0 h1"><?= htmlspecialchars($nama_koperasi) ?></span>
    </div>
    <div class="d-flex">
      <a href="dashboard_admin.php" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
      <a href="pengaturan.php" class="btn btn-outline-light btn-sm me-2">Pengaturan</a>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>
