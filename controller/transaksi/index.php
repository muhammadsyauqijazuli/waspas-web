<?php
$koneksi = new mysqli("localhost", "root", "", "bonus_evaluation_db");

$halaman = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;
$per_halaman = 10;
$offset = ($halaman - 1) * $per_halaman;

// Hitung total data untuk pagination
$result_total = $koneksi->query("SELECT COUNT(*) AS total FROM transaksi");
$total_data = $result_total->fetch_assoc()['total'] ?? 0;
$total_halaman = ceil($total_data / $per_halaman);

// Ambil data transaksi, urutkan dari ID terbesar (terbaru)
// UBAH BAGIAN INI: ORDER BY id DESC
$query = $koneksi->query("SELECT id, pemasukan, tanggal FROM transaksi ORDER BY id DESC LIMIT $offset, $per_halaman");

$transaksi = [];
while ($row = $query->fetch_assoc()) {
    $transaksi[] = $row;
}