<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = mysqli_connect("localhost", "root", "", "koperasi_tasemabe");
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, username, password, role, is_active, nomor_anggota FROM users WHERE username=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && $user['is_active'] == 1 && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            $_SESSION['nomor_anggota'] = $user['nomor_anggota'];

            if ($user['role'] === 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        } else {
            $error = "Login gagal. Username atau password salah, atau akun tidak aktif.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Koperasi Tasemabe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    body { background-color:#f8f9fa; }
    .login-card { max-width:400px; margin:auto; margin-top:80px; }
  </style>
</head>
<body>
<div class="container">
  <div class="card login-card shadow">
    <div class="card-body">
      <h3 class="text-center mb-4">🔑 Login Koperasi Tasemabe</h3>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
            <input type="password" name="password" id="password" class="form-control" required>
            <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
              <i class="bi bi-eye-slash"></i>
            </span>
          </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-box-arrow-in-right"></i> Login
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  const togglePassword = document.querySelector('#togglePassword');
  const password = document.querySelector('#password');
  const icon = togglePassword.querySelector('i');

  togglePassword.addEventListener('click', function () {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
  });
</script>
</body>
</html>
