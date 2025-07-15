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

    // Perhitungan WASPAS & Hybrid
    $bW = ['omset_pemasukan'=>0.40,'kecepatan'=>0.25,'kedisiplinan'=>0.20,'kepuasan'=>0.15];
    $bA = ['omset_pemasukan'=>0.35,'kecepatan'=>0.25,'kedisiplinan'=>0.25,'kepuasan'=>0.15];
    $lambda = 0.5;
    $maxOm = max($omsets);

    $details = [];
    foreach ($montirs as $m) {
        $o = $omsets[$m['id']];
        $n_om = $o / $maxOm;
        $n_k  = $m['kecepatan']    / 5;
        $n_d  = $m['kedisiplinan'] / 5;
        $n_p  = $m['kepuasan']     / 5;

        $sum  = $bW['omset_pemasukan']*$n_om
              + $bW['kecepatan']*$n_k
              + $bW['kedisiplinan']*$n_d
              + $bW['kepuasan']*$n_p;
        $prod = pow($n_om, $bW['omset_pemasukan'])
              * pow($n_k,   $bW['kecepatan'])
              * pow($n_d,   $bW['kedisiplinan'])
              * pow($n_p,   $bW['kepuasan']);
        $skW = $lambda*$sum + (1-$lambda)*$prod;

        $skH = $bA['omset_pemasukan']*$n_om
             + $bA['kecepatan']*$n_k
             + $bA['kedisiplinan']*$n_d
             + $bA['kepuasan']*$n_p;

        $details[] = [
            'id'=>$m['id'],'nama'=>$m['nama'],
            'omset'=>$o,
            'kecepatan'=>$m['kecepatan'],
            'kedisiplinan'=>$m['kedisiplinan'],
            'kepuasan'=>$m['kepuasan'],
            'skorWaspas'=>$skW,
            'skorHybrid'=>$skH
        ];
    }

    // Ranking
    $ws = array_column($details,'skorWaspas','id');
    arsort($ws);
    $hs = array_column($details,'skorHybrid','id');
    arsort($hs);
    $rankW = array_flip(array_keys($ws));
    $rankH = array_flip(array_keys($hs));

    foreach ($details as &$d) {
        $d['rankWaspas'] = $rankW[$d['id']] + 1;
        $d['rankHybrid'] = $rankH[$d['id']] + 1;
        $d['bonus'] =
            $d['skorHybrid'] >= 0.8 ? 'Rp 1.000.000' :
            ($d['skorHybrid'] >= 0.6 ? 'Rp 750.000' :
            ($d['skorHybrid'] >= 0.4 ? 'Rp 500.000' : 'Rp 250.000'));
    }
    unset($d);

    // Average
    $avgOm    = array_sum($omsets)/count($omsets);
    $avgWasp  = array_sum($ws)/count($ws);
    $avgHyb   = array_sum($hs)/count($hs);

    // Insight
    $best = reset($details);
    foreach ($details as $d) {
        if ($d['skorWaspas'] > $best['skorWaspas']) $best = $d;
    }
    $ins = ["Top performer: {$best['nama']} ({$best['skorHybrid']})"];

    echo json_encode([
        'labels' => array_column($details, 'nama'),
        'skorWaspas' => array_values($ws),
        'skorHybrid' => array_values($hs),
        'details' => $details,
        'avgOmset' => $avgOm,
        'avgWaspas' => $avgWasp,
        'avgHybrid' => $avgHyb,
        'insights' => $ins
    ], JSON_PRETTY_PRINT);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid action']);
