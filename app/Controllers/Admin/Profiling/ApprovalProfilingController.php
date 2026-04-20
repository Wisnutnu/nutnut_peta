<?php

namespace App\Controllers\Admin\Profiling;

use App\Controllers\BaseController;

class ApprovalProfilingController extends BaseController
{
    public function index()
{
    $db = \Config\Database::connect();

    $provinsi = $this->request->getGet('provinsi');
    $tahun    = $this->request->getGet('tahun');
    $search   = $this->request->getGet('search');

    // ================= PRODUKSI =================
    $produksiBuilder = $db->table('produksi');

    if ($provinsi) {
        $produksiBuilder->where('provinsi', $provinsi);
    }

    if ($tahun) {
        $produksiBuilder->where('tahun', $tahun);
    }

    if ($search) {
        $produksiBuilder->like('jenis_produksi', $search);
    }

    $produksi = $produksiBuilder
        ->orderBy('tahun', 'DESC')
        ->get()
        ->getResultArray();

    // ================= POPULASI =================
    $populasiBuilder = $db->table('populasi');

    if ($provinsi) {
        $populasiBuilder->where('provinsi', $provinsi);
    }

    if ($tahun) {
        $populasiBuilder->where('tahun', $tahun);
    }

    if ($search) {
        $populasiBuilder->like('jenis_ternak', $search);
    }

    $populasi = $populasiBuilder
        ->orderBy('tahun', 'DESC')
        ->get()
        ->getResultArray();

    // ================= HARGA =================
    $hargaBuilder = $db->table('harga');

    if ($provinsi) {
        $hargaBuilder->where('provinsi', $provinsi);
    }

    if ($tahun) {
        $hargaBuilder->where('tahun', $tahun);
    }

    if ($search) {
        $hargaBuilder->like('jenis_ternak', $search);
    }

    $harga = $hargaBuilder
        ->orderBy('tahun', 'DESC')
        ->get()
        ->getResultArray();

    return view('admin/approval_profiling', [
        'produksi' => $produksi,
        'populasi' => $populasi,
        'harga'    => $harga
    ]);
}

}

