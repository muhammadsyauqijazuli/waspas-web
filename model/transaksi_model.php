<?php
require_once __DIR__ . '/../db_koneksi.php';

function getTotalPemasukan($koneksi) {
    $query = $koneksi->query("SELECT SUM(pemasukan) AS total_pemasukan FROM transaksi");
    if (!$query) {
        die("Query total gagal: " . $koneksi->error);
    }
    $data = $query->fetch_assoc();
    return $data['total_pemasukan'] ?? 0;
}

function getPemasukanBulanan($koneksi) {
    $query = $koneksi->query("
        SELECT MONTH(tanggal) AS bulan, 
               SUM(pemasukan) AS total_bulanan 
        FROM transaksi 
        GROUP BY MONTH(tanggal)
        ORDER BY MONTH(tanggal)
    ");
    if (!$query) {
        die("Query bulanan gagal: " . $koneksi->error);
    }

    $data_bulanan = [];
    while ($row = $query->fetch_assoc()) {
        $data_bulanan[] = $row;
    }
    return $data_bulanan;
}


?>
