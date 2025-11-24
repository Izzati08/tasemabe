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

// Nama file backup
$date = date("Y-m-d_H-i-s");
$backupDir = __DIR__ . "/backup/";
if (!is_dir($backupDir)) { mkdir($backupDir, 0777, true); }
$backupFile = $backupDir . "db_backup_$date.sql";

// Perintah mysqldump (pastikan mysqldump ada di PATH XAMPP)
$command = "mysqldump --user=$user --password=$password --host=$host $database > \"$backupFile\"";

system($command, $output);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Backup Database</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <div class="card shadow">
    <div class="card-body">
      <h4 class="mb-3">Backup Database</h4>
      <?php if ($output === 0): ?>
        <div class="alert alert-success">✅ Backup berhasil: <?= htmlspecialchars($backupFile) ?></div>
      <?php else: ?>
        <div class="alert alert-danger">❌ Backup gagal!</div>
      <?php endif; ?>
      <a href="dashboard_admin.php" class="btn btn-secondary">⬅ Kembali ke Dashboard</a>
    </div>
  </div>
</body>
</html>
