<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "bonus_evaluation_db");

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$transaksi = null;

if ($id > 0) {
    $stmt = $koneksi->prepare("SELECT * FROM transaksi WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaksi = $result->fetch_assoc();
    $stmt->close();
}

// Jika data tidak ditemukan, redirect ke halaman utama
if (!$transaksi) {
    header("Location: /waspas-web/transaksi.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h3>Edit Transaksi</h3>
            </div>
            <div class="card-body">
                <form action="controller/transaksi/update_data.php" method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($transaksi['id']) ?>">

                    <div class="form-group mb-3">
                        <label for="pemasukan">Nominal Transaksi</label>
                        <input type="number" class="form-control" id="pemasukan" name="pemasukan"
                               value="<?= htmlspecialchars($transaksi['pemasukan']) ?>" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="tanggal">Tanggal Transaksi</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal"
                               value="<?= htmlspecialchars($transaksi['tanggal']) ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Data</button>
                    <a href="/waspas-web/transaksi.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>