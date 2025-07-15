<?php
// Mulai session
session_start();

// 1. Hubungkan ke database
$koneksi = new mysqli("localhost", "root", "", "bonus_evaluation_db");

// Variabel untuk pesan
$pesan_alert = "";

// 2. Cek apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $pemasukan = isset($_POST['pemasukan']) ? $_POST['pemasukan'] : '';
    $tanggal = isset($_POST['tanggal']) ? $_POST['tanggal'] : '';

    // Validasi sederhana
    if ($id > 0 && !empty($pemasukan) && !empty($tanggal)) {
        // 3. Siapkan query SQL UPDATE
        $sql = "UPDATE transaksi SET pemasukan = ?, tanggal = ? WHERE id = ?";
        $stmt = $koneksi->prepare($sql);

        if ($stmt) {
            // "sdi" berarti string, double/decimal, integer
            $stmt->bind_param("dsi", $pemasukan, $tanggal, $id);

            // 5. Eksekusi query dan siapkan pesan
            if ($stmt->execute()) {
                $pesan_alert = "Data berhasil diperbarui.";
            } else {
                $pesan_alert = "Gagal memperbarui data: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $pesan_alert = "Terjadi kesalahan pada query: " . $koneksi->error;
        }
    } else {
        $pesan_alert = "Semua field harus diisi.";
    }
    $koneksi->close();
} else {
    // Jika halaman diakses langsung tanpa POST, redirect
    header("Location: /waspas-web/transaksi.php");
    exit();
}

// 6. Tampilkan alert dan redirect menggunakan JavaScript
echo "<script>";
echo "alert('" . addslashes($pesan_alert) . "');";
echo "window.location.href = '/waspas-web/transaksi.php';";
echo "</script>";

exit();
?>