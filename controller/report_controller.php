<?php
// controller/report_controller.php
header('Content-Type: application/json');
require_once __DIR__ . '/../db_koneksi.php';  // sesuaikan path koneksi

try {
    $db = getConnection(); // fungsi dari db_koneksi.php
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error'=>'DB Connection failed']);
    exit;
}

$action = $_GET['action'] ?? null;

if ($action === 'get_montir') {
    $stmt = $db->query("SELECT id, nama FROM montir");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

if ($action === 'report') {
    // filter
    $from = $_GET['from'] ?: null;
    $to   = $_GET['to']   ?: null;
    $mid  = $_GET['montir'] ?: null;

    // 1) Ambil omset per montir
    $sqlT = "SELECT montir_id, SUM(pemasukan) as omset 
             FROM transaksi WHERE 1";
    $params = [];
    if ($from)  { $sqlT .= " AND tanggal >= :from";   $params[':from']=$from; }
    if ($to)    { $sqlT .= " AND tanggal <= :to";     $params[':to']=$to; }
    if ($mid)   { $sqlT .= " AND montir_id = :mid";   $params[':mid']=$mid; }
    $sqlT .= " GROUP BY montir_id";

    $stmt = $db->prepare($sqlT);
    $stmt->execute($params);
    $omsets = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // [montir_id=>omset]

    if (empty($omsets)) {
        echo json_encode([
          'labels'=>[], 'skorWaspas'=>[], 'skorHybrid'=>[],
          'details'=>[], 'avgOmset'=>0, 'avgWaspas'=>0, 'avgHybrid'=>0, 'insights'=>[]
        ]);
        exit;
    }

    // 2) Ambil data montir
    $ids = array_keys($omsets);
    $in  = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $db->prepare("SELECT id,nama,kecepatan,kedisiplinan,kepuasan 
                          FROM montir WHERE id IN ($in)");
    $stmt->execute($ids);
    $montirs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3) Bobot
    $bW = ['omset_pemasukan'=>0.40,'kecepatan'=>0.25,'kedisiplinan'=>0.20,'kepuasan'=>0.15];
    $bA = ['omset_pemasukan'=>0.35,'kecepatan'=>0.25,'kedisiplinan'=>0.25,'kepuasan'=>0.15];
    $lambda = 0.5;
    $maxOm = max($omsets);

    $details = [];
    foreach ($montirs as $m) {
        $o = $omsets[$m['id']];
        // normalisasi
        $n_om = $o / $maxOm;
        $n_k  = $m['kecepatan']    / 5;
        $n_d  = $m['kedisiplinan'] / 5;
        $n_p  = $m['kepuasan']     / 5;

        // WASPAS
        $sum  = $bW['omset_pemasukan']*$n_om
              + $bW['kecepatan']*$n_k
              + $bW['kedisiplinan']*$n_d
              + $bW['kepuasan']*$n_p;
        $prod = pow($n_om, $bW['omset_pemasukan'])
              * pow($n_k,   $bW['kecepatan'])
              * pow($n_d,   $bW['kedisiplinan'])
              * pow($n_p,   $bW['kepuasan']);
        $skW = $lambda*$sum + (1-$lambda)*$prod;

        // Hybrid (AHP‐weighted sum)
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
            'skorWaspas'=>$skW,'skorHybrid'=>$skH
        ];
    }

    // ranking
    $ws = array_column($details,'skorWaspas');
    arsort($ws);
    $hs = array_column($details,'skorHybrid');
    arsort($hs);
    $rankW = array_flip(array_keys($ws));
    $rankH = array_flip(array_keys($hs));

    // bonus & finalize
    foreach ($details as &$d) {
        $d['rankWaspas'] = $rankW[$d['id']]+1;
        $d['rankHybrid']= $rankH[$d['id']]+1;
        // threshold bonus
        if      ($d['skorHybrid']>=0.8) $bns='Rp 1.000.000';
        else if ($d['skorHybrid']>=0.6) $bns='Rp   750.000';
        else if ($d['skorHybrid']>=0.4) $bns='Rp   500.000';
        else                            $bns='Rp   250.000';
        $d['bonus']=$bns;
    }
    unset($d);

    // averages
    $avgOm    = array_sum($omsets)/count($omsets);
    $avgWasp  = array_sum(array_values($ws))/count($ws);
    $avgHyb   = array_sum(array_values($hs))/count($hs);

    // insight
    $best = reset($details);
    foreach ($details as $d) {
        if ($d['skorHybrid'] > $best['skorHybrid']) $best = $d;
    }
    $ins = ["Top performer: {$best['nama']} ({$best['skorHybrid']})"];

    echo json_encode([
      'labels'=>array_column($details,'nama'),
      'skorWaspas'=>array_values($ws),
      'skorHybrid'=>array_values($hs),
      'details'=>$details,
      'avgOmset'=>$avgOm,'avgWaspas'=>$avgWasp,'avgHybrid'=>$avgHyb,
      'insights'=>$ins
    ], JSON_PRETTY_PRINT);
    exit;
}

http_response_code(400);
echo json_encode(['error'=>'Invalid action']);
