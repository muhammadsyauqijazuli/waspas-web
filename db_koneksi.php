<?php
function getKoneksi() {
    $koneksi = new mysqli("localhost", "root", "", "bonus_evaluation_db");
    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }
    return $koneksi;
}

