<?php
$koneksi = new mysqli("localhost", "root", "", "bonus_evaluation_db");

if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $montir_id = $_POST['montir_id'];
  $pemasukan = $_POST['pemasukan'];
  $tanggal   = $_POST['tanggal'];

  $stmt = $koneksi->prepare("INSERT INTO transaksi (montir_id, pemasukan, tanggal) VALUES (?, ?, ?)");
  $stmt->bind_param("iis", $montir_id, $pemasukan, $tanggal);

  if ($stmt->execute()) {
    echo "<script>
      alert('Transaksi berhasil disimpan');
      window.location.href = '/waspas-web/transaksi.php';
    </script>";
  } else {
    echo "Gagal menyimpan data: " . $stmt->error;
  }

  $stmt->close();
}

$koneksi->close();
