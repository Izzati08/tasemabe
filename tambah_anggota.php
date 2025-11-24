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

// Proses tambah anggota jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor_anggota = $_POST['nomor_anggota'] ?? '';
    $nama_lengkap  = $_POST['nama_lengkap'] ?? '';
    $alamat        = $_POST['alamat'] ?? '';
    $no_hp         = $_POST['no_hp'] ?? '';

    if (empty($nomor_anggota) || empty($nama_lengkap)) {
        $error = "Nomor anggota dan nama lengkap wajib diisi.";
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO anggota (nomor_anggota, nama_lengkap, alamat, no_hp) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $nomor_anggota, $nama_lengkap, $alamat, $no_hp);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: data_anggota.php?msg=added");
            exit;
        } else {
            $error = "Gagal menambahkan data: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Anggota</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">➕ Tambah Anggota</span>
    <div class="d-flex">
      <a href="data_anggota.php" class="btn btn-outline-light btn-sm me-2">⬅ Data Anggota</a>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Konten -->
<div class="container mt-4">
  <div class="card shadow">
    <div class="card-body">
      <h4 class="mb-3">Form Tambah Anggota</h4>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nomor Anggota</label>
          <input type="text" name="nomor_anggota" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="nama_lengkap" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-control" rows="2"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">No HP</label>
          <input type="text" name="no_hp" class="form-control">
        </div>
        
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-success">
            <i class="bi bi-person-plus-fill"></i> Simpan Anggota Baru
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
