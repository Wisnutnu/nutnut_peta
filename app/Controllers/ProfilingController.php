<?php 

namespace App\Controllers;

use App\Models\PopulasiModel;
use App\Models\ProduksiModel;
use App\Models\HargaModel;

class ProfilingController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // ==========================
        // PROVINSI & KABUPATEN
        // ==========================
        $provinsi = $db->table('produksi')
                       ->distinct()
                       ->select('provinsi')
                       ->get()
                       ->getResultArray();

        $kabupaten = $db->table('produksi')
                        ->distinct()
                        ->select('kab_kota, provinsi')
                        ->get()
                        ->getResultArray();

        // ==========================
        // MODEL DATA CHART
        // ==========================
        $pop = new PopulasiModel();
        $prd = new ProduksiModel();
        $hrg = new HargaModel();

// ==========================
// DATA INFRASTRUKTUR (DISIAPKAN, BELUM DIPAKAI VIEW)
// ==========================
        $lokasi = [];

        $tables = [
            'klinikhewan' => ['nama_klinik', 'alamat_klinik', 'latitude', 'longitude'],
            'koperasipkh' => ['nama_koperasi', 'alamat', 'lat', 'lng'],
            'labbibit'    => ['nama_laboratorium', 'alamat', 'latitude', 'longitude'],
            'labkesmavet' => ['nama_laboratorium', 'alamat', 'latitude', 'longitude'],
            'labkeswan'   => ['nama_laboratorium', 'alamat', 'latitude', 'longitude'],
            'labpakan'    => ['nama_pabrik', 'alamat', 'latitude', 'longitude'],
            'pasarternak' => ['nama_pasar', 'alamat', 'latitude', 'longitude'],
            'puskeswan'   => ['nama_puskeswan', 'alamat', 'latitude', 'longitude'],
            'rph'         => ['nama_rph', 'alamat', 'latitude', 'longitude'],
            'sppg'        => ['nama', 'alamat', 'latitude', 'longitude'],
            'uph'         => ['nama_UPH', 'alamat', 'latitude', 'longitude'],
        ];

        foreach ($tables as $tbl => $cols) {
            $rows = $db->table($tbl)->get()->getResultArray();

            foreach ($rows as $row) {
                $lokasi[] = [
                    'jenis'     => $tbl,
                    'nama'      => $row[$cols[0]] ?? '-',
                    'alamat'    => $row[$cols[1]] ?? '-',
                    'kab_kota'  => $row['kab_kota'] ?? '-',
                    'provinsi'  => $row['provinsi'] ?? '-',
                    'lat'       => $row[$cols[2]] ?? 0,
                    'lng'       => $row[$cols[3]] ?? 0,
                ];
            }
        }

// ==========================
        // KIRIM KE VIEW
        // ==========================
        $data = [
            'provinsi'  => $provinsi,
            'kabupaten' => $kabupaten,
            'populasi'  => $pop->findAll(),
            'produksi'  => $prd->findAll(),
            'harga'     => $hrg->findAll(),
            'lokasi'    => $lokasi,   // ✅ sudah aman
        ];

        return view('profiling_view', $data);
    }

    // ============================================================
    //   AJAX: HITUNG PERSENTASE GROWTH  (3B Growth Bubble)
    // ============================================================
public function getGrowth()
{
    $prov = $this->request->getGet('provinsi');
    $kab  = $this->request->getGet('kabupaten');
    $tahunA = $this->request->getGet('tahun_awal');
    $tahunB = $this->request->getGet('tahun_akhir');

    $db = \Config\Database::connect();

    // ===============================
    // POPULASI
    // ===============================
    $pA = $db->table('populasi')->where([
        'provinsi' => $prov,
        'kab_kota' => $kab,
        'tahun' => $tahunA
    ])->get()->getRow();

    $pB = $db->table('populasi')->where([
        'provinsi' => $prov,
        'kab_kota' => $kab,
        'tahun' => $tahunB
    ])->get()->getRow();

    $popGrowth = 0;
    if ($pA && $pB && $pA->jumlah_populasi > 0) {
        $popGrowth = (($pB->jumlah_populasi - $pA->jumlah_populasi) / $pA->jumlah_populasi) * 100;
    }

    // ===============================
    // PRODUKSI
    // ===============================
    $prA = $db->table('produksi')->where([
        'provinsi' => $prov,
        'kab_kota' => $kab,
        'tahun' => $tahunA
    ])->get()->getRow();

    $prB = $db->table('produksi')->where([
        'provinsi' => $prov,
        'kab_kota' => $kab,
        'tahun' => $tahunB
    ])->get()->getRow();

    $prodGrowth = 0;
    if ($prA && $prB && $prA->jumlah > 0) {
        $prodGrowth = (($prB->jumlah - $prA->jumlah) / $prA->jumlah) * 100;
    }

    // ===============================
    // HARGA
    // ===============================
    $hrA = $db->table('harga')->where([
        'provinsi' => $prov,
        'kab_kota' => $kab,
        'tahun' => $tahunA
    ])->get()->getRow();

    $hrB = $db->table('harga')->where([
        'provinsi' => $prov,
        'kab_kota' => $kab,
        'tahun' => $tahunB
    ])->get()->getRow();

    $hargaGrowth = 0;
    if ($hrA && $hrB && $hrA->harga > 0) {
        $hargaGrowth = (($hrB->harga - $hrA->harga) / $hrA->harga) * 100;
    }

    return $this->response->setJSON([
        'populasi'  => round($popGrowth, 2),
        'produksi'  => round($prodGrowth, 2),
        'harga'     => round($hargaGrowth, 2)
    ]);
}

// untuk generate awal
public function getAnalysis()
{
    $prov = $this->request->getGet('provinsi');
    $kab  = $this->request->getGet('kabupaten');
    $tahunA = $this->request->getGet('tahun_awal');
    $tahunB = $this->request->getGet('tahun_akhir');

    $db = \Config\Database::connect();

    // Ambil data untuk tahun awal
    $pA = $db->table('populasi')->where(['provinsi'=>$prov, 'kab_kota'=>$kab, 'tahun'=>$tahunA])->get()->getRow();
    $prA = $db->table('produksi')->where(['provinsi'=>$prov, 'kab_kota'=>$kab, 'tahun'=>$tahunA])->get()->getRow();
    $hA = $db->table('harga')->where(['provinsi'=>$prov, 'kab_kota'=>$kab, 'tahun'=>$tahunA])->get()->getRow();

    // Ambil data untuk tahun akhir
    $pB = $db->table('populasi')->where(['provinsi'=>$prov, 'kab_kota'=>$kab, 'tahun'=>$tahunB])->get()->getRow();
    $prB = $db->table('produksi')->where(['provinsi'=>$prov, 'kab_kota'=>$kab, 'tahun'=>$tahunB])->get()->getRow();
    $hB = $db->table('harga')->where(['provinsi'=>$prov, 'kab_kota'=>$kab, 'tahun'=>$tahunB])->get()->getRow();

    // Hitung CAGR
    $years = max(1, ($tahunB - $tahunA));

    $cagr_pop = ($pA && $pB && $pA->jumlah_populasi > 0)
        ? pow($pB->jumlah_populasi / $pA->jumlah_populasi, 1/$years) - 1
        : 0;

    $cagr_prod = ($prA && $prB && $prA->jumlah > 0)
        ? pow($prB->jumlah / $prA->jumlah, 1/$years) - 1
        : 0;

    $cagr_harga = ($hA && $hB && $hA->harga > 0)
        ? pow($hB->harga / $hA->harga, 1/$years) - 1
        : 0;

    // AI-style analysis (manual rules)
    $analysisText = "Analisis Profiling untuk $kab, $prov:\n";

    if ($cagr_pop > 0) $analysisText .= "- Populasi ternak tumbuh " . round($cagr_pop*100,2) . "% per tahun.\n";
    else $analysisText .= "- Populasi ternak menunjukkan tren penurunan.\n";

    if ($cagr_prod > 0) $analysisText .= "- Produksi meningkat rata-rata " . round($cagr_prod*100,2) . "% per tahun.\n";
    else $analysisText .= "- Produksi menurun dibanding tahun sebelumnya.\n";

    if ($cagr_harga > 0) $analysisText .= "- Harga mengalami kenaikan stabil.\n";
    else $analysisText .= "- Harga mengalami tekanan turun.\n";

    // Cocok untuk kesimpulan
    if ($cagr_prod > $cagr_pop) {
        $analysisText .= "- Efisiensi produksi meningkat (produksi tumbuh lebih cepat dari populasi).\n";
    }

    // Ambil nilai terakhir
$last_pop  = $pB ? $pB->jumlah_populasi : 0;
$last_prod = $prB ? $prB->jumlah : 0;
$last_price = $hB ? $hB->harga : 0;

// === AI REKOMENDASI ===
$recommendation = $this->generateRecommendation(
    $cagr_pop*100,
    $cagr_prod*100,
    $cagr_harga*100
);

// === AI PREDIKSI ===
$prediction = $this->generatePrediction(
    $last_pop,
    $last_prod,
    $last_price,
    $cagr_pop*100,
    $cagr_prod*100,
    $cagr_harga*100
);

return $this->response->setJSON([
    'cagr' => [
        'populasi' => round($cagr_pop*100,2),
        'produksi' => round($cagr_prod*100,2),
        'harga'    => round($cagr_harga*100,2),
    ],
    'analysis'       => nl2br($analysisText),
    'recommendation' => nl2br($recommendation),
    'prediction'     => nl2br($prediction),
]);

}

//AI untuk analisis
private function generateAnalysis($prov, $kab, $growth)
{
    $pop = $growth['populasi'];
    $prod = $growth['produksi'];
    $harga = $growth['harga'];

    $txt = "Analisis Profiling untuk {$kab}, {$prov}:<br><br>";

    // --- Populasi ---
    if ($pop > 5) $txt .= "• Populasi ternak menunjukkan tren kenaikan yang konsisten.<br>";
    elseif ($pop < -5) $txt .= "• Populasi ternak mengalami penurunan signifikan.<br>";
    else $txt .= "• Populasi ternak cenderung stabil tanpa perubahan besar.<br>";

    // --- Produksi ---
    if ($prod > 5) $txt .= "• Produksi meningkat dan mengindikasikan peningkatan kinerja peternakan.<br>";
    elseif ($prod < -5) $txt .= "• Produksi menurun dibanding tahun sebelumnya.<br>";
    else $txt .= "• Produksi relatif stabil.<br>";

    // --- Harga ---
    if ($harga > 5) $txt .= "• Harga mengalami kenaikan signifikan kemungkinan karena pasokan terbatas.<br>";
    elseif ($harga < -5) $txt .= "• Harga mengalami penurunan.<br>";
    else $txt .= "• Harga stabil dengan fluktuasi kecil.<br>";

    // --- Efisiensi produksi ---
    if ($prod > $pop) $txt .= "• Efisiensi meningkat karena produksi tumbuh lebih cepat daripada populasi.<br>";
    else $txt .= "• Efisiensi menurun karena populasi tumbuh lebih cepat daripada produksi.<br>";

    return $txt;
}

// Ai rekomendasi
private function generateRecommendation($cagr_pop, $cagr_prod, $cagr_harga)
{
    $rec = "<b>Rekomendasi Strategis:</b><br>";

    // Populasi
    if ($cagr_pop < -5)
        $rec .= "• Populasi ternak menurun, perlu penguatan pembibitan/penambahan indukan.<br>";
    elseif ($cagr_pop > 5)
        $rec .= "• Populasi ternak meningkat, peluang ekspansi penggemukan terbuka.<br>";
    else
        $rec .= "• Populasi stabil, arahkan fokus ke efisiensi produksi.<br>";

    // Produksi
    if ($cagr_prod < -5)
        $rec .= "• Produksi menurun, evaluasi pakan, kualitas kandang, dan penyakit.<br>";
    elseif ($cagr_prod > 5)
        $rec .= "• Produksi meningkat, perkuat rantai distribusi dan pasar.<br>";
    else
        $rec .= "• Produksi stagnan, optimasi manajemen pakan dan panen.<br>";

    // Harga
    if ($cagr_harga > 5)
        $rec .= "• Harga naik → potensi keuntungan lebih tinggi, perbesar skala produksi.<br>";
    elseif ($cagr_harga < -5)
        $rec .= "• Harga turun → stabilisasi pasokan dan efisiensi biaya diperlukan.<br>";
    else
        $rec .= "• Harga relatif stabil, cocok untuk pengembangan jangka panjang.<br>";

    // Kombinasi khusus
    if ($cagr_prod > $cagr_pop)
        $rec .= "• Produksi tumbuh lebih cepat dari populasi → efisiensi meningkat, pertimbangkan investasi pabrik pakan.<br>";

    if ($cagr_pop < 0 && $cagr_prod < 0)
        $rec .= "• Semua indikator turun → perlu intervensi pemerintah/penyuluhan.<br>";

    return $rec;
}

// Ai prediksi tahun depan 
private function generatePrediction($last_pop, $last_prod, $last_price, $cagr_pop, $cagr_prod, $cagr_harga)
{
    $pred = "<b>Prediksi Tahun Depan:</b><br>";

    $pred_pop = $last_pop * (1 + ($cagr_pop/100));
    $pred_prod = $last_prod * (1 + ($cagr_prod/100));
    $pred_price = $last_price * (1 + ($cagr_harga/100));

    $pred .= "• Populasi: " . number_format($pred_pop, 0) . "<br>";
    $pred .= "• Produksi: " . number_format($pred_prod, 0) . "<br>";
    $pred .= "• Harga: Rp " . number_format($pred_price, 0) . "<br>";

    return $pred;
}


}

