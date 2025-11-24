<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Formulir Pendaftaran Anggota</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #4CAF50, #2E7D32);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      border-radius: 15px;
      overflow: hidden;
    }
    .card-header {
      background-color: #388E3C;
    }
    .logo {
      height: 40px;
      margin-right: 10px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card shadow-lg w-100" style="max-width: 500px; margin: auto;">
    <div class="card-header d-flex align-items-center text-white">
      <!-- Logo koperasi -->
      <img src="logo.png" alt="Logo Koperasi" class="logo">
      <!-- Judul dan subjudul -->
      <div>
        <h4 class="mb-0">Formulir Pendaftaran Anggota</h4>
        <small>Koperasi Tasemabe</small>
      </div>
    </div>
    <div class="card-body p-4">
      <form method="POST" action="proses.php">
        <!-- Tanggal Daftar -->
        <div class="mb-3">
          <label class="form-label">Tanggal Daftar</label>
          <input type="date" name="tanggal_daftar" class="form-control" required>
        </div>
        <!-- Nama Lengkap -->
        <div class="mb-3">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama sesuai KTP" required>
        </div>
        <!-- No KTP -->
        <div class="mb-3">
          <label class="form-label">No KTP</label>
          <input type="text" name="no_ktp" class="form-control" 
                 placeholder="Nomor Induk Kependudukan" 
                 pattern="\d{16}" title="Masukkan 16 digit angka" required>
        </div>
        <!-- Alamat Lengkap -->
        <div class="mb-3">
          <label class="form-label">Alamat Lengkap</label>
          <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat sesuai KTP atau domisili" required></textarea>
        </div>
        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-success w-100">Daftar Sekarang</button>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
