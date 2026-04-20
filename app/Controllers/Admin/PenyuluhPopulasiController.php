<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Penyuluh\PopulasiPenyuluhModel;

class PenyuluhPopulasiController extends BaseController
{
    protected $populasi;

    public function __construct()
    {
        $this->populasi = new PopulasiPenyuluhModel();
    }

    public function index()
    {
        $data['populasi'] = $this->populasi
            ->select('populasi_penyuluh.*, pokok_master_jenis_ternak.nama_jenis')
            ->join('pokok_master_jenis_ternak', 'pokok_master_jenis_ternak.id = populasi_penyuluh.jenis_ternak_id')
            ->where('populasi_penyuluh.status', 'disetujui')
            ->findAll();

        return view('admin/penyuluh/index', $data);
    }
}