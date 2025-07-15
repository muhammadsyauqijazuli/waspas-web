<?php
// Selalu mulai session di baris paling atas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah variabel session 'admin_logged_in' ada dan bernilai true
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Jika tidak, redirect menggunakan header PHP dan hentikan eksekusi script
    header("Location: /waspas-web/login.php");
    exit(); // Pastikan tidak ada kode lain yang dieksekusi setelah redirect
}