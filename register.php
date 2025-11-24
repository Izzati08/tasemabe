<?php
$conn = mysqli_connect("localhost","root","","koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($username === '' || $password === '') {
    $error = "Form belum lengkap.";
  } else {
    // cek apakah username sudah ada
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if (mysqli_fetch_assoc($res)) {
      $error = "Username sudah dipakai, silakan pilih username lain.";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt2 = mysqli_prepare($conn, "INSERT INTO users (username, password, role, is_active) VALUES (?, ?, 'anggota', 1)");
      mysqli_stmt_bind_param($stmt2, "ss", $username, $hash);
      if (mysqli_stmt_execute($stmt2)) {
        $success = "Registrasi berhasil. Silakan login.";
      } else {
        $error = "Terjadi kesalahan saat menyimpan data.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register Akun Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body { background-color:#f8f9fa; }
    .card { border-radius:12px; }
    .input-group-text {
      background-color: transparent;
      border-left: none;
      cursor: pointer;
    }
    .form-control:focus { box-shadow:none; }
    .btn { border-radius:8px; }
  </style>
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
<div class="container px-3">
  <div class="card shadow-lg mx-auto" style="max-width:420px;">
    <div class="card-header bg-success text-white text-center">
      <h4>Buat Akun Login</h4>
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
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required autocomplete="username">
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
              <input type="password" name="password" id="password" class="form-control" required autocomplete="new-password">
              <span class="input-group-text" onclick="togglePassword()">
                <i class="bi bi-eye" id="toggleIcon"></i>
              </span>
            </div>
          </div>
          <button type="submit" class="btn btn-success w-100">Register</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
function togglePassword(){
  const pass=document.getElementById("password");
  const icon=document.getElementById("toggleIcon");
  if(pass.type==="password"){
    pass.type="text";
    icon.classList.remove("bi-eye");
    icon.classList.add("bi-eye-slash");
  } else {
    pass.type="password";
    icon.classList.remove("bi-eye-slash");
    icon.classList.add("bi-eye");
  }
}
</script>
</body>
</html>
