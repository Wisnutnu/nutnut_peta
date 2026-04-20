<?php

namespace App\Controllers;

use App\Models\PopulasiModel;
use App\Models\ProduksiModel;
use App\Models\HargaModel;

class AIController extends BaseController
{
    public function insight()
    {
        $pop = (new PopulasiModel())->findAll();
        $pro = (new ProduksiModel())->findAll();
        $har = (new HargaModel())->findAll();

        // ---- RINGKAS DATA ----
        $summary = [
            "populasi_trend" => $this->trend($pop, "jumlah_populasi"),
            "produksi_trend" => $this->trend($pro, "jumlah"),
            "harga_trend"    => $this->trend($har, "harga"),
        ];

        // ---- AI SEDERHANA: LOGIC BERDASARKAN TREN ----
        $recommend = [];

        if ($summary["populasi_trend"] == "naik_kuat") {
            $recommend[] = "Populasi hewan meningkat signifikan → peluang pembangunan pabrik pakan.";
        }

        if ($summary["produksi_trend"] == "turun") {
            $recommend[] = "Produksi turun → perlu program peningkatan budidaya.";
        }

        if ($summary["harga_trend"] == "naik") {
            $recommend[] = "Harga naik → indikasi kekurangan suplai → perlu intervensi pemerintah.";
        }

        return $this->response->setJSON([
            "summary" => $summary,
            "rekomendasi" => $recommend
        ]);
    }

    private function trend($data, $field)
    {
        $clean = array_filter(array_map(fn ($x) => (float)$x[$field], $data));

        if (count($clean) < 2) return "data_tidak_cukup";

        $first = reset($clean);
        $last  = end($clean);

        if ($last > $first * 1.3) return "naik_kuat";
        if ($last > $first)      return "naik";
        if ($last == $first)     return "stabil";
        if ($last < $first)      return "turun";

        return "tidak_diketahui";
    }
}
