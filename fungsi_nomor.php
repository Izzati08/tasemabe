<?php
function generateNomorAnggota($tanggal_daftar, $conn) {
    // BLTH: MMYY
    $blth = date("my", strtotime($tanggal_daftar));

    // Ambil nomor urut terakhir dari database (global, tidak reset per BLTH)
    $sql = "SELECT nomor_anggota 
            FROM anggota 
            WHERE nomor_anggota LIKE 'TSMB-%' 
            ORDER BY id DESC 
            LIMIT 1";
    $res = mysqli_query($conn, $sql);

    $nextUrut = 1;
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        // nomor_anggota contoh: TSMB-0525-000123
        $parts = explode('-', $row['nomor_anggota']);
        if (count($parts) === 3) {
            $lastUrut = intval($parts[2]);
            $nextUrut = $lastUrut + 1;
        }
    }

    $nourut = str_pad($nextUrut, 6, "0", STR_PAD_LEFT);
    return "TSMB-$blth-$nourut";
}
?>
