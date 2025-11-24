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

// Ambil ID anggota dari URL
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID anggota tidak ditemukan.");
}

// Ambil data anggota berdasarkan ID
$stmt = mysqli_prepare($conn, "SELECT id, nomor_anggota, nama_lengkap, alamat, no_hp FROM anggota WHERE id=?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$anggota = mysqli_fetch_assoc($res);

if (!$anggota) {
    die("Data anggota tidak ditemukan.");
}

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor_anggota = $_POST['nomor_anggota'] ?? '';
    $nama_lengkap  = $_POST['nama_lengkap'] ?? '';
    $alamat        = $_POST['alamat'] ?? '';
    $no_hp         = $_POST['no_hp'] ?? '';

    if (empty($nomor_anggota) || empty($nama_lengkap)) {
        $error = "Nomor anggota dan nama lengkap wajib diisi.";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE anggota SET nomor_anggota=?, nama_lengkap=?, alamat=?, no_hp=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssssi", $nomor_anggota, $nama_lengkap, $alamat, $no_hp, $id);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Data anggota berhasil diperbarui.";
            // Refresh data anggota setelah update
            $anggota['nomor_anggota'] = $nomor_anggota;
            $anggota['nama_lengkap']  = $nama_lengkap;
            $anggota['alamat']        = $alamat;
            $anggota['no_hp']         = $no_hp;
        } else {
            $error = "Gagal memperbarui data: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Anggota</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">✏️ Edit Anggota</span>
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
      <h4 class="mb-3">Form Edit Anggota</h4>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
      <?php endif; ?>
      <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nomor Anggota</label>
          <input type="text" name="nomor_anggota" class="form-control" value="<?= e($anggota['nomor_anggota']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="nama_lengkap" class="form-control" value="<?= e($anggota['nama_lengkap']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Alamat</label>
          <textarea name="alamat" class="form-control" rows="2"><?= e($anggota['alamat']) ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">No HP</label>
          <input type="text" name="no_hp" class="form-control" value="<?= e($anggota['no_hp']) ?>">
        </div>
        
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-warning">
            <i class="bi bi-save"></i> Simpan Perubahan
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
