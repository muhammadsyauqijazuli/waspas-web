<?php
// Mengatur header agar outputnya adalah JSON
header('Content-Type: application/json');

// --- KONEKSI DATABASE ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nama_database_anda"; // <-- GANTI DENGAN NAMA DATABASE ANDA

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    // Jika koneksi gagal, kirim response error dalam format JSON
    echo json_encode(['error' => 'Koneksi gagal: ' . $conn->connect_error]);
    exit(); // Hentikan eksekusi skrip
}

// --- LOGIKA PENGAMBILAN DATA ---
$sql = "SELECT m.nama, o.total_omset 
        FROM omset_montir AS o 
        JOIN montir AS m ON o.montir_id = m.id";

$result = $conn->query($sql);

$labels = [];
$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $labels[] = $row["nama"];
        $data[] = (float) $row["total_omset"]; // Konversi ke float untuk konsistensi
    }
}

// Tutup koneksi
$conn->close();

// --- MEMBUAT RESPONSE JSON ---
// Gabungkan kedua array ke dalam satu array asosiatif
$response = [
    'labels' => $labels,
    'data' => $data
];

// Cetak hasil akhir dalam format JSON
echo json_encode($response);

// Tidak perlu tag penutup ?> jika file ini hanya berisi PHP