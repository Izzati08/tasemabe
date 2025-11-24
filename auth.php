<?php
// Mulai session hanya jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fungsi helper untuk aman output HTML
if (!function_exists('e')) {
    function e($str) {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "koperasi_tasemabe");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Validasi login: jika belum login, arahkan ke login.php
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

// Fungsi helper untuk validasi role
function require_role($role) {
    if ($_SESSION['role'] !== $role) {
        // Jika role tidak sesuai, arahkan ke dashboard default
        header("Location: dashboard.php");
        exit;
    }
}
