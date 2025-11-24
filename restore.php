<?php
require_once __DIR__ . '/auth.php';

// Validasi hanya admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Konfigurasi database
$host     = "localhost";
$user     = "root";
$password = "";
$database = "koperasi_tasemabe";

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['backup_file']['tmp_name'])) {
        $backupFile = $_FILES['backup_file']['tmp_name'];

        // Perintah mysql untuk restore
        $command = "mysql --user=$user --password=$password --host=$host $database < $backupFile";

        system($command, $output);

        if ($output === 0) {
            $msg = "✅ Restore berhasil dari file: " . htmlspecialchars($_FILES['backup_file']['name']);
        } else {
            $msg = "❌ Restore gagal!";
        }
    } else {
        $msg = "Silakan pilih file backup terlebih dahulu.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Restore Database</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <span class="navbar-brand">🗄️ Restore Database</span>
    <div class="d-flex">
      <a href="dashboard_admin.php" class="btn btn-outline-light btn-sm me-2">⬅ Dashboard Admin</a>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="card shadow">
    <div class="card-body">
      <h4 class="mb-3">Form Restore Database</h4>

      <?php if (!empty($msg)): ?>
        <div class="alert alert-info"><?= $msg ?></div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Pilih File Backup (.sql)</label>
          <input type="file" name="backup_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-upload"></i> Restore Database
        </button>
        <a href="dashboard_admin.php" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Batal / Kembali ke Dashboard
        </a>
      </form>
    </div>
  </div>
</div>

<footer class="text-center mt-4 mb-2 text-muted">
  <small>&copy; <?= date('Y') ?> Koperasi Tasemabe. Semua hak dilindungi.</small>
</footer>
</body>
</html>
