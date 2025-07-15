<?php
// 1. Establish database connection
$koneksi = new mysqli("localhost", "root", "", "bonus_evaluation_db");

// Check connection
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// 2. Prepare and execute the query to get all mechanics
$sqlMontir = "SELECT id, nama FROM montir";
$queryMontir = $koneksi->query($sqlMontir);

// 3. Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the necessary POST variables are set
    if (isset($_POST['montir_id'], $_POST['pemasukan'], $_POST['tanggal'])) {
        $montir_id = $_POST['montir_id'];
        $pemasukan = $_POST['pemasukan'];
        $tanggal   = $_POST['tanggal'];

        // Use prepared statements to prevent SQL injection
        $stmt = $koneksi->prepare("INSERT INTO transaksi (montir_id, pemasukan, tanggal) VALUES (?, ?, ?)");
        // 'i' for integer (id), 'd' for double (pemasukan), 's' for string (tanggal)
        $stmt->bind_param("ids", $montir_id, $pemasukan, $tanggal);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Transaksi berhasil disimpan');
                    window.location.href = '/waspas-web/transaksi.php'; // Adjusted path for simplicity
                  </script>";
        } else {
            echo "Gagal menyimpan data: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Form data is incomplete.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Transaksi</title>
    </head>
<body>

    <form action="" method="POST">
        <div class="form-group mb-3">
            <label for="idKaryawan">ID Karyawan</label>
            <select class="form-control" id="idKaryawan" name="montir_id" required>
                <option value="" disabled selected>Pilih Nama Montir...</option>
                <?php
                // 4. Populate the dropdown menu
                if ($queryMontir->num_rows > 0) {
                    // Rewind the result set to the beginning if you've used it before
                    $queryMontir->data_seek(0);
                    // Loop through the results and create an option for each mechanic
                    while ($montir = $queryMontir->fetch_assoc()) {
                        // Use htmlspecialchars to prevent XSS attacks
                        echo '<option value="' . $montir['id'] . '">' . htmlspecialchars($montir['nama']) . '</option>';
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group mb-3">
             <label for="pemasukan">Pemasukan</label>
             <input type="number" class="form-control" id="pemasukan" name="pemasukan" required>
        </div>

        <div class="form-group mb-3">
             <label for="tanggal">Tanggal</label>
             <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
    </form>

</body>
</html>

<?php
// 5. Close the database connection at the end of the script
$koneksi->close();
?>