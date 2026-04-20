<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\UploadInfrastrukturModel;

class UploadInfrastrukturController extends BaseController
{
    public function index()
    {
        $model = new UploadInfrastrukturModel();

        $data['riwayatinfrastruktur'] = $model
            ->where('user_id', session()->get('user_id'))
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('user/infrastruktur/index', $data);
    }

    public function save()
    {
        $model = new UploadInfrastrukturModel();

        $data = [
            'user_id' => session()->get('user_id'),
            'jenis_infrastruktur' => $this->request->getPost('jenis'),
            'nama_tempat' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'status' => 'pending'
        ];

        $model->insert($data);

        return redirect()->to('/user/infrastruktur')
                         ->with('success', 'Data berhasil dikirim');
    }
}