<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DataPokok\StagingDataPokokModel;

class DataPokokController extends BaseController
{
    protected $stagingModel;

    public function __construct()
    {
        $this->stagingModel = new StagingDataPokokModel();
    }

    public function index()
    {
        $data = $this->stagingModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin/datapokok/index', [
            'data' => $data
        ]);
    }

    public function edit($id)
{
    $row = $this->stagingModel->find($id);

    return view('admin/datapokok/edit', [
        'row' => $row
    ]);
}

public function update($id)
{
    $this->stagingModel->update($id, [
        'populasi' => $this->request->getPost('populasi'),
        'pemotongan_rph' => $this->request->getPost('pemotongan_rph'),
    ]);

    return redirect()->to('admin/datapokok');
}

    public function approve($id)
    {
        $row = $this->stagingModel->find($id);

        if (!$row) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $db = \Config\Database::connect();

        // pindahkan ke tabel resmi
        $db->table('pokok_data_ternak')->insert([
            'provinsi_id' => $row['provinsi_id'],
            'kabupaten_id' => $row['kabupaten_id'],
            'jenis_ternak_id' => $row['jenis_ternak_id'],
            'tahun' => $row['tahun'],
            'populasi' => $row['populasi'],

            'pemotongan_rph' => $row['pemotongan_rph'],
            'pemotongan_luar_rph' => $row['pemotongan_luar_rph'],
            'pemotongan_tidak_tercatat' => $row['pemotongan_tidak_tercatat'],
        ]);

        // update status staging
        $this->stagingModel->update($id, [
            'status' => 'approved'
        ]);

        return redirect()->back()->with('success', 'Data berhasil di approve');
    }

    public function reject($id)
    {
        $this->stagingModel->update($id, [
            'status' => 'rejected'
        ]);

        return redirect()->back()->with('success', 'Data ditolak');
    }
}
