<?php
require_once __DIR__ . '/auth.php';
require 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

if (!isset($_GET['code']) || $_GET['code'] === '') {
  die("Kode tidak ditemukan.");
}

$code = e($_GET['code']); // gunakan fungsi e() dari auth.php

$generator = new BarcodeGeneratorPNG();
$barcode = $generator->getBarcode($code, $generator::TYPE_CODE_128);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Barcode Anggota</title>
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- penting untuk layar HP -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color:#f8f9fa; }
    .card { border-radius:12px; }
    .barcode-img { max-width:100%; height:auto; }
    .btn { border-radius:8px; }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;">
<div class="container px-3">
  <div class="card shadow-lg text-center">
    <div class="card-header bg-success text-white">
      <h5 class="mb-0">Barcode Anggota</h5>
    </div>
    <div class="card-body">
      <p class="mb-2">Nomor Anggota: <strong><?= e($code) ?></strong></p>
      <img src="data:image/png;base64,<?= base64_encode($barcode) ?>" alt="Barcode" class="barcode-img mb-3">
      <div class="d-grid">
        <a href="dashboard.php" class="btn btn-primary">
          ⬅️ Kembali ke Dashboard
        </a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
