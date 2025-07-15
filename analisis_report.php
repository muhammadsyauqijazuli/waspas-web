<?php
include 'controller/auth.php';
require_once __DIR__ . '/model/transaksi_model.php';

$total_pemasukan = getTotalPemasukan($koneksi);
$data_bulanan = getPemasukanBulanan($koneksi);
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

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text mx-3">WASPAS WEB</div>
            </a>

            <!-- Nav Item - Dashboard -->
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
                <li class="nav-item"><a class="nav-link" href="index.php"><i
                            class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li class="nav-item"><a class="nav-link" href="input-data.php"><i
                            class="fas fa-fw fa-edit"></i><span>Input Data</span></a></li>
                <li class="nav-item"><a class="nav-link" href="transaksi.php"><i
                            class="fas fa-fw fa-exchange-alt"></i><span>Transaksi</span></a></li>
                <li class="nav-item active"><a class="nav-link" href="charts.php"><i
                            class="fas fa-fw fa-chart-area"></i><span>Analisis Report</span></a></li>
                <hr class="sidebar-divider d-none d-md-block">
                <div class="text-center d-none d-md-inline"><button class="rounded-circle border-0"
                        id="sidebarToggle"></button></div>
            </ul>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="controller/transaksi/logout.php" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <!-- HEADER & FILTER -->
                <header class="container-fluid bg-white shadow-sm py-3 mb-4">
                    <div class="container d-flex flex-wrap align-items-center justify-content-between">
                        <h2 class="mb-2 mb-md-0">Analisis Report Bonus Montir</h2>
                        <form id="filter-form" class="d-flex flex-wrap align-items-center gap-2">
                            <input type="date" name="from" class="form-control" placeholder="Dari" />
                            <input type="date" name="to" class="form-control" placeholder="Sampai" />
                            <select name="montir" class="form-select">
                                <option value="">-- Semua Montir --</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Terapkan</button>
                        </form>
                    </div>
                </header>

                <main class="container">

                    <!-- RINGKASAN KPI -->
                    <section class="row g-3 mb-4">
                        <?php
                        $kpis = [
                            ['id' => 'avg-omset', 'label' => 'Avg. Omset'],
                            ['id' => 'avg-waspas', 'label' => 'Avg. Skor WASPAS'],
                            ['id' => 'avg-hybrid', 'label' => 'Avg. Skor Hybrid'],
                            ['id' => 'delta-score', 'label' => 'Selisih (%)'],
                        ];
                        foreach ($kpis as $k): ?>
                            <div class="col-sm-6 col-md-3">
                                <div class="card shadow-sm p-3 text-center">
                                    <h6 class="text-secondary"><?= $k['label'] ?></h6>
                                    <h3 id="<?= $k['id'] ?>">–</h3>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </section>

                    <!-- GRAFIK PERBANDINGAN -->
                    <section class="card shadow-sm mb-4 p-3">
                        <h5 class="mb-3">Perbandingan Skor per Montir</h5>
                        <canvas id="chart-skor-montir" height="100"></canvas>
                    </section>

                    <!-- TABEL DETAIL -->
                    <section class="card shadow-sm mb-4 p-3">
                        <h5 class="mb-3">Detail Hasil Perhitungan</h5>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Montir</th>
                                        <th>Omset</th>
                                        <th>Kecepatan</th>
                                        <th>Kedisiplinan</th>
                                        <th>Kepuasan</th>
                                        <th>Skor WASPAS</th>
                                        <th>Skor Hybrid</th>
                                        <th>Rank (WASPAS)</th>
                                        <th>Rank (Hybrid)</th>
                                        <th>Bonus</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-report"></tbody>
                            </table>
                        </div>
                    </section>

                    <!-- INSIGHT & REKOMENDASI -->
                    <section class="card shadow-sm mb-4 p-3">
                        <h5 class="mb-3">Insight & Rekomendasi</h5>
                        <ul id="insight-list" class="mb-0"></ul>
                    </section>

                    <!-- EXPORT & PRINT -->
                    <footer class="d-flex justify-content-end gap-2 mb-5">
                        <button id="export-excel" class="btn btn-outline-secondary">Export Excel</button>
                        <button id="export-pdf" class="btn btn-outline-secondary">Export PDF</button>
                        <button class="btn btn-primary" onclick="window.print()">Print</button>
                    </footer>

                </main>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; WASPAS WEB 2025</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="login.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script>
            const phpLabels = <?= json_encode(array_map(function ($row) {
                $bulan = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];
                return $bulan[(int) $row['bulan']];
            }, $data_bulanan)); ?>;

            const phpData = <?= json_encode(array_column($data_bulanan, 'total_bulanan')); ?>;
        </script>
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Load dropdown montir
    (async function loadMontir() {
      const resp = await fetch('controller/report_controller.php?action=get_montir');
      const montirs = await resp.json();
      const sel = document.querySelector('select[name="montir"]');
      montirs.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id;
        opt.textContent = m.nama;
        sel.appendChild(opt);
      });
    })();

    // Handle form submit
    document.getElementById('filter-form').addEventListener('submit', async e => {
      e.preventDefault();
      const params = new URLSearchParams(new FormData(e.target));
      const resp = await fetch('controller/report_controller.php?action=report&' + new URLSearchParams(new FormData(e.target)));
      const data = await resp.json();

      // KPI
      document.getElementById('avg-omset').textContent = 'Rp ' + data.avgOmset.toLocaleString();
      document.getElementById('avg-waspas').textContent = data.avgWaspas.toFixed(3);
      document.getElementById('avg-hybrid').textContent = data.avgHybrid.toFixed(3);
      const delta = ((data.avgHybrid - data.avgWaspas) / data.avgWaspas) * 100;
      document.getElementById('delta-score').textContent = delta.toFixed(1) + '%';

      // Chart
      const ctx = document.getElementById('chart-skor-montir').getContext('2d');
      if (window.montirChart) window.montirChart.destroy();
      window.montirChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.labels,
          datasets: [
            { label: 'WASPAS', data: data.skorWaspas },
            { label: 'Hybrid', data: data.skorHybrid }
          ]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
      });

      // Tabel
      const tbody = document.getElementById('tbody-report');
      tbody.innerHTML = '';
      data.details.forEach((d, i) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${i+1}</td>
          <td>${d.nama}</td>
          <td>Rp ${d.omset.toLocaleString()}</td>
          <td>${d.kecepatan}</td>
          <td>${d.kedisiplinan}</td>
          <td>${d.kepuasan}</td>
          <td>${d.skorWaspas.toFixed(3)}</td>
          <td>${d.skorHybrid.toFixed(3)}</td>
          <td>${d.rankWaspas}</td>
          <td>${d.rankHybrid}</td>
          <td>${d.bonus}</td>
        `;
        tbody.appendChild(tr);
      });

      // Insight
      const insightUl = document.getElementById('insight-list');
      insightUl.innerHTML = '';
      data.insights.forEach(text => {
        const li = document.createElement('li');
        li.textContent = text;
        insightUl.appendChild(li);
      });
    });
  </script>
</body>

</html>