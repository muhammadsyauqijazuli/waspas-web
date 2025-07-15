<?php
/**
 * model_pendapatan.php
 * File ini berfungsi sebagai model untuk mengambil data pendapatan dari database.
 */

/**
 * Mengambil data nama montir dan total omsetnya dari database.
 *
 * @return array Array yang berisi data pendapatan atau array kosong jika tidak ada data.
 */
function ambilDataPendapatan() {
    // --- Konfigurasi Koneksi Database ---
    $servername = "localhost";      // Ganti dengan server Anda
    $username = "root";             // Ganti dengan username database Anda
    $password = "";                 // Ganti dengan password database Anda
    $dbname = "bonus_evaluation_db"; // Ganti dengan nama database Anda

    // Buat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Cek koneksi
    if ($conn->connect_error) {
        // Sebaiknya dicatat ke log, bukan langsung die di aplikasi nyata
        error_log("Koneksi database gagal: " . $conn->connect_error);
        return []; // Kembalikan array kosong jika koneksi gagal
    }

    // --- Query SQL ---
    // Menggabungkan tabel 'montir' dan 'omset_montir'
    $sql = "SELECT m.nama, o.total_omset 
            FROM montir m
            JOIN omset_montir o ON m.id = o.montir_id";

    $result = $conn->query($sql);

    $data = [];
    if ($result && $result->num_rows > 0) {
        // Ambil semua baris hasil query ke dalam array
        $data = $result->fetch_all(MYSQLI_ASSOC);
    }

    // Tutup koneksi
    $conn->close();

    return $data;
}
?>