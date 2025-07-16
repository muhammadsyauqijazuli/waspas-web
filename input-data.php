<?php
// Memastikan sesi sudah dimulai dan pengguna sudah login
include 'controller/auth.php';

// Memanggil controller montir. Controller ini sudah termasuk koneksi DB dan menangani semua aksi (add/delete).
include 'controller/transaksi/montir_controller.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Input Data Montir - WASPAS WEB</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar (Sama seperti sebelumnya, tidak perlu diubah) -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text mx-3">WASPAS WEB</div>
            </a>
            <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
            <li class="nav-item active"><a class="nav-link" href="input-data.php"><i class="fas fa-fw fa-edit"></i><span>Input Data</span></a></li>
            <li class="nav-item"><a class="nav-link" href="transaksi.php"><i class="fas fa-fw fa-exchange-alt"></i><span>Transaksi</span></a></li>
            <li class="nav-item"><a class="nav-link" href="analisis_report.php"><i class="fas fa-fw fa-chart-area"></i><span>Analisis Report</span></a></li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline"><button class="rounded-circle border-0" id="sidebarToggle"></button></div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar (Sama seperti sebelumnya, tidak perlu diubah) -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Input Data Montir</h1>

                    <?php
                    // Menampilkan notifikasi berdasarkan status dari URL
                    if (isset($_GET['status'])) {
                        $status = $_GET['status'];
                        $alert_type = 'danger';
                        $message = '';

                        if ($status == 'success_add') {
                            $alert_type = 'success';
                            $message = '<strong>Berhasil!</strong> Data montir telah berhasil disimpan.';
                        } elseif ($status == 'success_delete') {
                            $alert_type = 'success';
                            $message = '<strong>Berhasil!</strong> Data montir telah berhasil dihapus.';
                        } elseif ($status == 'error') {
                            $message = '<strong>Gagal!</strong> ' . (isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Terjadi kesalahan.');
                        }
                        
                        if ($message) {
                            echo "<div class='alert alert-{$alert_type} alert-dismissible fade show' role='alert'>
                                    {$message}
                                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                  </div>";
                        }
                    }
                    ?>

                    <!-- Form untuk Input Data Montir -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Form Input Data Montir</h6>
                        </div>
                        <div class="card-body">
                            <!-- Form sekarang mengirim data ke halaman ini sendiri (action kosong) -->
                            <form action="" method="POST">
                                <div class="form-group mb-3">
                                    <label for="namaMontir">Nama Montir</label>
                                    <input type="text" class="form-control" id="namaMontir" name="namaMontir" placeholder="Masukkan nama montir" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="kecepatan" class="form-label">Kecepatan</label>
                                    <input type="number" class="form-control" id="kecepatan" name="kecepatan" min="0" max="100" placeholder="Masukkan nilai 0-100" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="kedisiplinan" class="form-label">Kedisiplinan</label>
                                    <input type="number" class="form-control" id="kedisiplinan" name="kedisiplinan" min="0" max="100" placeholder="Masukkan nilai 0-100" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="kepuasan" class="form-label">Kepuasan</label>
                                    <input type="number" class="form-control" id="kepuasan" name="kepuasan" min="0" max="100" placeholder="Masukkan nilai 0-100" required>
                                </div>
                                <!-- Tambahkan name pada tombol submit untuk identifikasi di controller -->
                                <button type="submit" name="simpan_montir" class="btn btn-primary">Simpan Data Montir</button>
                            </form>
                        </div>
                    </div>

                    <!-- Tabel untuk Menampilkan Data Montir -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Montir Tersimpan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Kecepatan</th>
                                            <th>Kedisiplinan</th>
                                            <th>Kepuasan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($montirData)) : ?>
                                            <?php foreach ($montirData as $montir) : ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($montir['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($montir['nama']); ?></td>
                                                    <td><?php echo htmlspecialchars($montir['kecepatan']); ?></td>
                                                    <td><?php echo htmlspecialchars($montir['kedisiplinan']); ?></td>
                                                    <td><?php echo htmlspecialchars($montir['kepuasan']); ?></td>
                                                    <td>
                                                        <a href="#" class="btn btn-warning btn-sm btn-circle"><i class="fas fa-pen"></i></a>
                                                        <!-- Link hapus sekarang menunjuk ke halaman ini dengan parameter action -->
                                                        <a href="input-data.php?action=delete&id=<?php echo $montir['id']; ?>" class="btn btn-danger btn-sm btn-circle" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="6" class="text-center">Belum ada data montir yang tersimpan.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto"><span>Copyright &copy; WASPAS WEB 2025</span></div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Siap untuk Keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">Pilih "Logout" di bawah ini jika Anda siap untuk mengakhiri sesi Anda.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
