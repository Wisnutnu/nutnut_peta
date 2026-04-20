<?php

namespace App\Controllers\Penyuluh;

use App\Controllers\BaseController;
use App\Models\PenyuluhPemotonganHarianModel;

class InputPemotongan extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new PenyuluhPemotonganHarianModel();
    }

    public function index()
    {
        $data['data'] = $this->model
            ->where('user_id', session()->get('id'))
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        return view('user/penyuluh/pemotongan', $data);
    }

    public function create()
{
    return view('user/penyuluh/pemotongan_create');
}

public function store()
{
    // Validasi sederhana dulu
    $rules = [
        'nama_tempat' => 'required',
        'tanggal' => 'required',
        'sapi_potong' => 'required|integer',
        'sapi_perah' => 'required|integer',
        'kerbau' => 'required|integer'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Cek duplikat tanggal + tempat
    $cek = $this->model
        ->where('user_id', session()->get('id'))
        ->where('nama_tempat', $this->request->getPost('nama_tempat'))
        ->where('tanggal', $this->request->getPost('tanggal'))
        ->first();

    if ($cek) {
        return redirect()->back()->withInput()
            ->with('error', 'Data untuk tanggal ini sudah ada.');
    }

    $this->model->save([
        'user_id'      => session()->get('id'),
        'nama_tempat'  => $this->request->getPost('nama_tempat'),
        'alamat'       => $this->request->getPost('alamat'),
        'desa'         => $this->request->getPost('desa'),
        'kecamatan'    => $this->request->getPost('kecamatan'),
        'kabupaten'    => $this->request->getPost('kabupaten'),
        'bulan'        => $this->request->getPost('bulan'),
        'tanggal'      => $this->request->getPost('tanggal'),
        'nama_petugas' => $this->request->getPost('nama_petugas'),
        'sapi_potong'  => $this->request->getPost('sapi_potong'),
        'sapi_perah'   => $this->request->getPost('sapi_perah'),
        'kerbau'       => $this->request->getPost('kerbau'),
        'status'       => 'pending'
    ]);

    return redirect()->to('/user/penyuluh')
        ->with('success', 'Data berhasil disimpan.');
}
}