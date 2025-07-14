<?php
session_start();

// 1. Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "bonus_evaluation_db");
if ($koneksi->connect_error) {
    error_log("Koneksi DB gagal: " . $koneksi->connect_error);
    $_SESSION['login_error'] = 'Terjadi kesalahan koneksi ke database.';
    header('Location: ../../login.php');
    exit;
}

// 2. Tangkap input POST (dan escape untuk keamanan)
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

// 3. Validasi input
if ($username === '' || $password === '') {
    $_SESSION['login_error'] = 'Username dan password wajib diisi.';
    header('Location: ../../login.php');
    exit;
}

// 4. Siapkan dan jalankan query
$stmt = $koneksi->prepare("
    SELECT id, username, password
    FROM admin_users
    WHERE username = ?
    LIMIT 1
");
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// 5. Verifikasi hasil
if ($user) {
    // Jika password disimpan plain-text (tidak disarankan):
    if ($password === $user['password']) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id']        = $user['id'];
        $_SESSION['admin_user']      = $user['username'];
        // Redirect ke menu utama
        header('Location: ../../index.php');
        exit;
    }
    // Jika menggunakan hash bcrypt/argon2:
    // if (password_verify($password, $user['password'])) { … }
}

// 6. Jika login gagal
$_SESSION['login_error'] = 'Username atau password salah.';
header('Location: ../../login.php');
exit;
