<?php

namespace App\Controllers\User\Kabupaten;

use App\Controllers\BaseController;
use App\Models\Penyuluh\PopulasiPenyuluhModel;

class ValidasiPopulasiController extends BaseController
{
    protected $populasi;

    public function __construct()
    {
        $this->populasi = new PopulasiPenyuluhModel();
    }

    public function index()
    {
        $kabupaten_id = session()->get('kabupaten_id');

        // DATA MASUK
        $data['masuk'] = $this->populasi
            ->where('kabupaten_id', $kabupaten_id)
            ->where('status', 'diajukan')
            ->findAll();

        // DATA SUDAH PROSES
        $data['selesai'] = $this->populasi
            ->where('kabupaten_id', $kabupaten_id)
            ->whereIn('status', ['disetujui', 'ditolak'])
            ->findAll();

        return view('user/kabupaten/populasi/index', $data);
    }

    public function approve($id)
    {
        $this->populasi->update($id, [
            'status' => 'disetujui'
        ]);

        return redirect()->back()->with('success', 'Data disetujui');
    }

    public function reject($id)
    {
        $this->populasi->update($id, [
            'status' => 'ditolak'
        ]);

        return redirect()->back()->with('success', 'Data ditolak');
    }
}