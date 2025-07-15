<?php
/**
 * File: controller/montir_controller.php
 * Deskripsi: Controller tunggal untuk mengelola semua operasi CRUD (Create, Read, Delete) untuk data montir.
 */

// FIX: Menghapus include file dan membuat koneksi langsung.
// Menggunakan nama variabel $conn secara konsisten.
$conn = new mysqli("localhost", "root", "", "bonus_evaluation_db");

// FIX: Menambahkan pengecekan koneksi database.
// Jika koneksi gagal, hentikan eksekusi dan tampilkan pesan error.
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}


// --- FUNGSI-FUNGSI LOGIKA ---

/**
 * Mengambil semua data montir dari database.
 * @param mysqli $conn Objek koneksi database.
 * @return array Data montir.
 */
function getAllMontir($conn) {
    $data = [];
    $sql = "SELECT id, nama, kecepatan, kedisiplinan, kepuasan FROM montir";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

/**
 * Menambah data montir baru ke database.
 * @param mysqli $conn Objek koneksi database.
 */
function addMontir($conn) {
    // Mengambil dan membersihkan data dari form
    $nama = $conn->real_escape_string($_POST['namaMontir']);
    $kecepatan = (int)$_POST['kecepatan'];
    $kedisiplinan = (int)$_POST['kedisiplinan'];
    $kepuasan = (int)$_POST['kepuasan'];

    // Logika untuk membuat ID Otomatis (Contoh: MTR001, MTR002)
    $query_last_id = "SELECT id FROM montir ORDER BY id DESC LIMIT 1";
    $result_last_id = $conn->query($query_last_id);
    $new_num = 1;
    if ($result_last_id && $result_last_id->num_rows > 0) {
        $last_id_row = $result_last_id->fetch_assoc();
        $last_num = (int)substr($last_id_row['id'], 3);
        $new_num = $last_num + 1;
    }
    $new_id = 'MTR' . str_pad($new_num, 3, '0', STR_PAD_LEFT);

    // Query untuk memasukkan data baru menggunakan prepared statement
    $sql = "INSERT INTO montir (id, nama, kecepatan, kedisiplinan, kepuasan) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiii", $new_id, $nama, $kecepatan, $kedisiplinan, $kepuasan);

    if ($stmt->execute()) {
        header("Location: input-data.php?status=success_add");
    } else {
        header("Location: input-data.php?status=error&message=" . urlencode($stmt->error));
    }
    $stmt->close();
    exit();
}

/**
 * Menghapus data montir berdasarkan ID.
 * @param mysqli $conn Objek koneksi database.
 */
function deleteMontir($conn) {
    $id = $conn->real_escape_string($_GET['id']);
    $sql = "DELETE FROM montir WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        header("Location: input-data.php?status=success_delete");
    } else {
        header("Location: input-data.php?status=error&message=" . urlencode($stmt->error));
    }
    $stmt->close();
    exit();
}


// --- PENGENDALI PERMINTAAN (REQUEST HANDLER) ---

// Memeriksa jika ada permintaan POST untuk menambah data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['simpan_montir'])) {
    addMontir($conn);
}

// Memeriksa jika ada permintaan GET untuk menghapus data
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    deleteMontir($conn);
}

// Selalu ambil semua data montir untuk ditampilkan di view.
// FIX: Tidak perlu lagi memeriksa 'isset($conn)' karena koneksi sudah dipastikan di atas.
$montirData = getAllMontir($conn);

?>
