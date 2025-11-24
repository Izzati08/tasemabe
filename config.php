<?php
$DB_HOST = 'sqlxxx.epizy.com'; // ganti sesuai MySQL Details
$DB_NAME = 'epiz_12345678_koperasi';
$DB_USER = 'epiz_12345678';    // atau sesuai user DB
$DB_PASS = 'PASSWORD_DB_KAMU'; // jangan pakai password akun jika ada password DB terpisah

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_error) {
    die('Koneksi gagal: ' . $mysqli->connect_error);
}

// Base URL dinamis
$BASE_URL = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/';
?>
