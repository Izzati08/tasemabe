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

$error = '';
$success = '';

// Ambil pengaturan koperasi (misalnya tabel `pengaturan`)
$result = mysqli_query($conn, "SELECT * FROM pengaturan LIMIT 1");
$pengaturan = mysqli_fetch_assoc($result);

// Proses update pengaturan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_koperasi = $_POST['nama_koperasi'] ?? '';
    $alamat        = $_POST['alamat'] ?? '';
    $logo          = $pengaturan['logo'] ?? '';

    // Upload logo jika ada file baru
    if (!empty($_FILES['logo']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) { mkdir($targetDir, 0777, true); }
        $logoFile = $targetDir . basename($_FILES['logo']['name']);
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $logoFile)) {
            $logo = $logoFile;
        }
    }

    if (empty($nama_koperasi)) {
        $error = "Nama koperasi wajib diisi.";
    } else {
        if ($pengaturan) {
            $stmt = mysqli_prepare($conn, "UPDATE pengaturan SET nama_koperasi=?, alamat=?, logo=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "sssi", $nama_koperasi, $alamat, $logo, $pengaturan['id']);
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO pengaturan (nama_koperasi, alamat, logo) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $nama_koperasi, $alamat, $logo);
        }

        if (mysqli_stmt_execute($stmt)) {
            $success = "Pengaturan berhasil disimpan.";
            $pengaturan['nama_koperasi'] = $nama_koperasi;
            $pengaturan['alamat']        = $alamat;
            $pengaturan['logo']          = $logo;
        } else {
            $error = "Gagal menyimpan pengaturan: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pengaturan Koperasi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">⚙️ Pengaturan Koperasi</span>
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
      <h4 class="mb-3">Form Pengaturan</h4>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Nama Koperasi</label>
          <input type="text" name="nama_koperasi" class="form-control" value="<?= e($pengaturan['nama_koperasi'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-control" rows="2"><?= e($pengaturan['alamat'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Logo</label>
          <?php if (!empty($pengaturan['logo'])): ?>
            <div class="mb-2">
              <img src="<?= e($pengaturan['logo']) ?>" alt="Logo Koperasi" style="max-height:80px;">
            </div>
          <?php endif; ?>
          <input type="file" name="logo" class="form-control">
        </div>
        
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan Pengaturan
          </button>
          <a href="dashboard_admin.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Batal / Kembali ke Dashboard
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="text-center mt-4 mb-2 text-muted">
  <small>&copy; <?= date('Y') ?> Koperasi Tasemabe. Semua hak dilindungi.</small>
</footer>
</body>
</html>
