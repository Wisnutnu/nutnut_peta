<?php namespace App\Controllers;

use App\Models\ProduksiModel;
use App\Models\PopulasiModel;
use App\Models\HargaModel;

class ApiData extends BaseController
{
public function profiling()
{
    $produksiM = new \App\Models\ProduksiModel();
    $populasiM = new \App\Models\PopulasiModel();
    $hargaM    = new \App\Models\HargaModel();

    // ambil semua data 2020–2024
    $years = [2020, 2021, 2022, 2023, 2024];

    // hasil final
    $result = [];

    foreach ($years as $th) {

        // PRODUKSI
        $produksi = $produksiM
            ->select('kab_kota, tahun, SUM(jumlah) as total')
            ->where('tahun', $th)
            ->groupBy('kab_kota, tahun')
            ->findAll();

        // POPULASI
        $populasi = $populasiM
            ->select('kab_kota, tahun, SUM(jumlah_populasi) as total')
            ->where('tahun', $th)
            ->groupBy('kab_kota, tahun')
            ->findAll();

        // HARGA
        $harga = $hargaM
            ->select('kab_kota, tahun, AVG(harga) as total')
            ->where('tahun', $th)
            ->groupBy('kab_kota, tahun')
            ->findAll();

        $result[$th] = [
            'produksi' => $produksi,
            'populasi' => $populasi,
            'harga'    => $harga
        ];
    }

    return $this->response->setJSON($result);
}


}