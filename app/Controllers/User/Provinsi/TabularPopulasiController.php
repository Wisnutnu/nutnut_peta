<?php

namespace App\Controllers\User\Provinsi;

use App\Controllers\BaseController;

class TabularPopulasiController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $provinsi_id = session()->get('provinsi_id');

        $data['populasi'] = $db->table('populasi_penyuluh')
            ->select('populasi_penyuluh.*, master_kabupaten.nama_kabupaten')
            ->join('master_kabupaten', 'master_kabupaten.id = populasi_penyuluh.kabupaten_id', 'left')
            ->where('populasi_penyuluh.provinsi_id', $provinsi_id)
            ->where('populasi_penyuluh.status', 'disetujui')
            ->get()
            ->getResult();

        return view('User/Provinsi/tabularpopulasi', $data);
    }
}