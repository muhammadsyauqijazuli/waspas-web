<?php
// Mulai session (jika belum)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login sebagai admin
if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Jika belum, redirect ke halaman login
    header('Location: ../login.php');
    exit;
}
