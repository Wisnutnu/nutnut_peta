<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ManagementUser\UserModel;
use App\Models\ManagementUser\ProvinsiModel;
use App\Models\ManagementUser\KabupatenModel;
use App\Models\ManagementUser\KecamatanModel;

class UserController extends BaseController
{
    public function index()
    {
        $model = new UserModel();

        $data['users'] = $model
            ->select('users.*, 
                    master_provinsi.nama_provinsi,
                    master_kabupaten.nama_kabupaten,
                    master_kecamatan.nama_kecamatan')
            ->join('master_provinsi', 'master_provinsi.id = users.provinsi_id', 'left')
            ->join('master_kabupaten', 'master_kabupaten.id = users.kabupaten_id', 'left')
            ->join('master_kecamatan', 'master_kecamatan.id = users.kecamatan_id', 'left')
            ->findAll();

        return view('admin/managementuser/index', $data);
    }

    public function create()
    {
        $provinsiModel = new ProvinsiModel();

        $data['provinsi'] = $provinsiModel->findAll();

        return view('admin/managementuser/create', $data);
    }

    public function store()
    {
        $model = new UserModel();

        $level = $this->request->getPost('level_user');

        $provinsi_id = $this->request->getPost('provinsi_id');
        $kabupaten_id = $this->request->getPost('kabupaten_id');
        $kecamatan_id = $this->request->getPost('kecamatan_id');

        // LOGIC LEVEL
        if ($level == 'provinsi') {
            $kabupaten_id = null;
            $kecamatan_id = null;
        }

        if ($level == 'kabupaten') {
            $kecamatan_id = null;
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role' => $this->request->getPost('role'),
            'level_user' => $level,
            'provinsi_id' => $provinsi_id,
            'kabupaten_id' => $kabupaten_id,
            'kecamatan_id' => $kecamatan_id,
            'is_active' => 1
        ];

        $model->insert($data);

        session()->setFlashdata('success', 'User berhasil ditambahkan');
        return redirect()->to('/admin/managementuser');
    }

    public function edit($id)
{
    $model = new UserModel();
    return $this->response->setJSON($model->find($id));
}

    public function update($id)
    {
        $model = new \App\Models\ManagementUser\UserModel();

        $model->update($id, [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'role' => $this->request->getPost('role'),
            'provinsi_id' => $this->request->getPost('provinsi_id'),
            'kabupaten_id' => $this->request->getPost('kabupaten_id'),
            'kecamatan_id' => $this->request->getPost('kecamatan_id'),
        ]);

        return redirect()->to('/admin/managementuser')
            ->with('success', 'User berhasil diupdate');
    }
}