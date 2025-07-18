<?php
// controller/report_controller.php
header('Content-Type: application/json');
require_once __DIR__ . '/../db_koneksi.php'; // Sesuaikan path

$koneksi = getKoneksi(); // Fungsi dari db_koneksi.php

$action = $_GET['action'] ?? null;

if ($action === 'get_montir') {
    $result = $koneksi->query("SELECT id, nama FROM montir");
    $montirs = [];
    while ($row = $result->fetch_assoc()) {
        $montirs[] = $row;
    }
    echo json_encode($montirs);
    exit;
}

if ($action === 'report') {
    $from = $_GET['from'] ?? null;
    $to   = $_GET['to'] ?? null;
    $mid  = $_GET['montir'] ?? null;

    // Query omset per montir
    $sqlT = "SELECT montir_id, SUM(pemasukan) as omset FROM transaksi WHERE 1";
    if ($from) $sqlT .= " AND tanggal >= '$from'";
    if ($to)   $sqlT .= " AND tanggal <= '$to'";
    if ($mid)  $sqlT .= " AND montir_id = '$mid'";
    $sqlT .= " GROUP BY montir_id";

    $result = $koneksi->query($sqlT);
    $omsets = [];
    while ($row = $result->fetch_assoc()) {
        $omsets[$row['montir_id']] = (float)$row['omset'];
    }

    if (empty($omsets)) {
        echo json_encode([
            'labels'=>[], 'skorWaspas'=>[], 'skorHybrid'=>[],
            'details'=>[], 'avgOmset'=>0, 'avgWaspas'=>0, 'avgHybrid'=>0, 'insights'=>[]
        ]);
        exit;
    }

    // Ambil data montir
    $ids = array_keys($omsets);
    $idList = implode(",", array_map('intval', $ids));
    $result = $koneksi->query("SELECT id, nama, kecepatan, kedisiplinan, kepuasan FROM montir WHERE id IN ($idList)");

    $montirs = [];
    while ($row = $result->fetch_assoc()) {
        $montirs[] = $row;
    }

    // ==================================================================
    // == BLOK PERHITUNGAN (WSM + WPM) UNTUK WASPAS & HYBRID AHP‑WASPAS ==
    // ==================================================================

    // 1. Kumpulkan semua nilai untuk normalisasi
    $all_kecepatan    = array_column($montirs, 'kecepatan');
    $all_kedisiplinan = array_column($montirs, 'kedisiplinan');
    $all_kepuasan     = array_column($montirs, 'kepuasan');

    $max_omset    = max(array_values($omsets));
    $max_kepuasan = max($all_kepuasan);

    $min_kecepatan    = min($all_kecepatan);
    $min_kedisiplinan = min($all_kedisiplinan);

    // 2. Bobot WASPAS awal (WSM + WPM)
    $bW = [
        'omset_pemasukan' => 0.40,
        'kecepatan'       => 0.25,
        'kedisiplinan'    => 0.20,
        'kepuasan'        => 0.15
    ];
    // 3. Parameter lambda
    $lambda = 0.5;

    // 4. Siapkan perbandingan berpasangan AHP (urutan: omset, kecepatan, kedisiplinan, kepuasan)
    $P = [
        [1,   2,   2,   3],
        [1/2, 1, 1/1.25, 2],
        [1/2, 1.25, 1,   3],
        [1/3, 1/2, 1/3, 1]
    ];
    $criteria_keys = ['omset_pemasukan','kecepatan','kedisiplinan','kepuasan'];

    // 5. Hitung bobot AHP dengan metode geometric mean
    $gm = [];
    foreach ($P as $i => $row) {
        $prod = 1.0;
        foreach ($row as $val) {
            $prod *= $val;
        }
        // nth root, n = jumlah kriteria = 4
        $gm[$i] = pow($prod, 1/count($row));
    }
    $sum_gm = array_sum($gm);
    $bA = [];
    foreach ($gm as $i => $val) {
        $bA[$criteria_keys[$i]] = $val / $sum_gm;
    }

    $details = [];
    foreach ($montirs as $m) {
        $o = $omsets[$m['id']];

        // --- Normalisasi ---
        $n_om = $max_omset > 0 ? ($o / $max_omset) : 0;
        $n_p  = $max_kepuasan > 0 ? ($m['kepuasan'] / $max_kepuasan) : 0;
        $n_k  = $m['kecepatan'] > 0 ? ($min_kecepatan / $m['kecepatan']) : 0;
        $n_d  = $m['kedisiplinan'] > 0 ? ($min_kedisiplinan / $m['kedisiplinan']) : 0;

        // --- WASPAS ---
        $sumW = $bW['omset_pemasukan'] * $n_om
              + $bW['kecepatan']       * $n_k
              + $bW['kedisiplinan']    * $n_d
              + $bW['kepuasan']        * $n_p;
        $prodW = pow($n_om, $bW['omset_pemasukan'])
               * pow($n_k,  $bW['kecepatan'])
               * pow($n_d,  $bW['kedisiplinan'])
               * pow($n_p,  $bW['kepuasan']);
        $skW = $lambda * $sumW + (1 - $lambda) * $prodW;

        // --- HYBRID AHP‑WASPAS ---
        $sumA = $bA['omset_pemasukan'] * $n_om
              + $bA['kecepatan']       * $n_k
              + $bA['kedisiplinan']    * $n_d
              + $bA['kepuasan']        * $n_p;
        $prodA = pow($n_om, $bA['omset_pemasukan'])
               * pow($n_k,  $bA['kecepatan'])
               * pow($n_d,  $bA['kedisiplinan'])
               * pow($n_p,  $bA['kepuasan']);
        $skH = $lambda * $sumA + (1 - $lambda) * $prodA;

        $details[] = [
            'id'           => $m['id'],
            'nama'         => $m['nama'],
            'omset'        => $o,
            'kecepatan'    => $m['kecepatan'],
            'kedisiplinan' => $m['kedisiplinan'],
            'kepuasan'     => $m['kepuasan'],
            'skorWaspas'   => $skW,
            'skorHybrid'   => $skH
        ];
    }
    // ==================================================================
    // == AKHIR PERHITUNGAN ==
    // ==================================================================

    // Ranking
    $ws = array_column($details, 'skorWaspas', 'id');
    arsort($ws);
    $hs = array_column($details, 'skorHybrid', 'id');
    arsort($hs);
    $rankW = array_flip(array_keys($ws));
    $rankH = array_flip(array_keys($hs));

    usort($details, function($a, $b) {
        return $b['skorWaspas'] <=> $a['skorWaspas'];
    });

    foreach ($details as &$d) {
        $d['rankWaspas'] = $rankW[$d['id']] + 1;
        $d['rankHybrid'] = $rankH[$d['id']] + 1;
        $d['bonus'] =
            $d['skorHybrid'] >= 0.8 ? 'Rp 1.000.000' :
            ($d['skorHybrid'] >= 0.6 ? 'Rp 750.000' :
            ($d['skorHybrid'] >= 0.4 ? 'Rp 500.000' : 'Rp 250.000'));
    }
    unset($d);

    // Averages
    $avgOm  = array_sum($omsets) / count($omsets);
    $avgW   = array_sum($ws)     / count($ws);
    $avgH   = array_sum($hs)     / count($hs);

    // Insight
    $best = $details[0];
    $ins  = ["Top performer: {$best['nama']} (skor {$best['skorWaspas']})"];

    // Output JSON
    echo json_encode([
        'labels'     => array_column($details, 'nama'),
        'skorWaspas' => array_column($details, 'skorWaspas'),
        'skorHybrid' => array_column($details, 'skorHybrid'),
        'details'    => $details,
        'avgOmset'   => $avgOm,
        'avgWaspas'  => $avgW,
        'avgHybrid'  => $avgH,
        'insights'   => $ins
    ], JSON_PRETTY_PRINT);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid action']);
?>
