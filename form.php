<?php
require_once __DIR__ . '/auth.php'; // sudah ada session & koneksi

$error = '';
$success = '';
$nomor_anggota = $_SESSION['nomor_anggota'] ?? '';

// Generate nomor anggota otomatis jika belum ada
if ($nomor_anggota === '' || $nomor_anggota === null) {
    $tanggal_daftar = date('Y-m-d');
    $blth = date("my", strtotime($tanggal_daftar));

    $sql = "SELECT nomor_anggota FROM anggota WHERE nomor_anggota LIKE 'TSMB-$blth-%' ORDER BY id DESC LIMIT 1";
    $res = mysqli_query($conn, $sql);
    $nextUrut = 1;
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $parts = explode('-', $row['nomor_anggota']);
        if (count($parts) === 3) {
            $lastUrut = intval($parts[2]);
            $nextUrut = $lastUrut + 1;
        }
    }
    $nourut = str_pad($nextUrut, 6, "0", STR_PAD_LEFT);
    $nomor_anggota = "TSMB-$blth-$nourut";

    $_SESSION['nomor_anggota'] = $nomor_anggota;
    $stmt = mysqli_prepare($conn, "UPDATE users SET nomor_anggota=? WHERE username=?");
    mysqli_stmt_bind_param($stmt, "ss", $nomor_anggota, $_SESSION['username']);
    mysqli_stmt_execute($stmt);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama   = $_POST['nama_lengkap'] ?? '';
    $ktp    = $_POST['no_ktp'] ?? '';
    $hp     = $_POST['no_hp'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $tanggal= $_POST['tanggal_daftar'] ?? date('Y-m-d');

    if ($nama === '' || $ktp === '' || $hp === '' || $alamat === '') {
        $error = "Form belum lengkap.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id FROM anggota WHERE nomor_anggota=?");
        mysqli_stmt_bind_param($stmt, "s", $nomor_anggota);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if (mysqli_fetch_assoc($res)) {
            $stmt2 = mysqli_prepare($conn, "UPDATE anggota SET nama_lengkap=?, no_ktp=?, no_hp=?, alamat=?, tanggal_daftar=? WHERE nomor_anggota=?");
            mysqli_stmt_bind_param($stmt2, "ssssss", $nama, $ktp, $hp, $alamat, $tanggal, $nomor_anggota);
            mysqli_stmt_execute($stmt2);
        } else {
            $stmt2 = mysqli_prepare($conn, "INSERT INTO anggota (nomor_anggota, nama_lengkap, no_ktp, no_hp, alamat, tanggal_daftar) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt2, "ssssss", $nomor_anggota, $nama, $ktp, $hp, $alamat, $tanggal);
            mysqli_stmt_execute($stmt2);
        }
        $success = "Data pendaftaran berhasil disimpan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Formulir Pendaftaran Anggota</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- penting untuk layar HP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color:#f8f9fa; }
    .card { border-radius:12px; }
    .form-label { font-weight:500; }
    .btn { border-radius:8px; }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;">
<div class="container px-3">
  <div class="card shadow-lg w-100">
    <div class="card-header bg-primary text-white text-center">
      <h5 class="mb-0">Formulir Pendaftaran Anggota</h5>
    </div>
    <div class="card-body">
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert alert-success text-center">
          <?= e($success) ?>
        </div>
        <div class="d-grid mt-3">
          <a href="dashboard.php" class="btn btn-primary">
            ⬅️ Kembali ke Dashboard
          </a>
        </div>
      <?php else: ?>
        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Nomor Anggota (otomatis)</label>
            <input type="text" class="form-control" value="<?= e($nomor_anggota) ?>" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan nama lengkap" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nomor KTP</label>
            <input type="text" name="no_ktp" class="form-control" placeholder="Masukkan nomor KTP" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nomor HP</label>
            <input type="text" name="no_hp" class="form-control" placeholder="Masukkan nomor HP aktif" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal Daftar</label>
            <input type="date" name="tanggal_daftar" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>
          <button type="submit" class="btn btn-success w-100 py-2">Simpan Data</button>
          <a href="dashboard.php" class="btn btn-link w-100 mt-2">Kembali ke Dashboard</a>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
