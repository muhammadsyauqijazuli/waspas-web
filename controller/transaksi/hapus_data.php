<?php
// Mulai session untuk bisa menggunakan variabel session
session_start();

// 1. Hubungkan ke database
$koneksi = new mysqli("localhost", "root", "", "bonus_evaluation_db");

// Variabel untuk menyimpan pesan alert
$pesan_alert = "";

// 2. Cek apakah ada ID yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // 3. Siapkan query SQL DELETE
    $sql = "DELETE FROM transaksi WHERE id = ?";
    $stmt = $koneksi->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);

        // 5. Eksekusi query dan siapkan pesan
        if ($stmt->execute()) {
            $pesan_alert = "Data berhasil dihapus.";
        } else {
            $pesan_alert = "Gagal menghapus data.";
        }
        $stmt->close();
    } else {
        $pesan_alert = "Terjadi kesalahan pada query.";
    }

    $koneksi->close();
} else {
    $pesan_alert = "ID tidak ditemukan.";
}

// 6. Tampilkan alert dan redirect menggunakan JavaScript
echo "<script>";
echo "alert('" . addslashes($pesan_alert) . "');"; // Menampilkan alert dengan pesan
echo "window.location.href = '/waspas-web/transaksi.php';"; // Redirect setelah alert ditutup
echo "</script>";

exit(); // Hentikan eksekusi skrip
?>