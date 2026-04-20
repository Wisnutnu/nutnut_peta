<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\ManagementUser\UserModel;


namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\ManagementUser\UserModel;

class ProfileController extends BaseController
{

    public function index()
    {
        $model = new UserModel();

        $user = $model
            ->select('users.*, 
                master_provinsi.nama_provinsi,
                master_kabupaten.nama_kabupaten,
                master_kecamatan.nama_kecamatan')
            ->join('master_provinsi', 'master_provinsi.id = users.provinsi_id', 'left')
            ->join('master_kabupaten', 'master_kabupaten.id = users.kabupaten_id', 'left')
            ->join('master_kecamatan', 'master_kecamatan.id = users.kecamatan_id', 'left')
            ->where('users.id', session()->get('id_user'))
            ->first();

        return view('user/profile', ['user' => $user]);
    }

// update profil
    public function update()
    {
        $model = new UserModel();
        $id = session()->get('id_user');

        $model->update($id, [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
        ]);

        // update session juga
        session()->set([
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
        ]);

        return redirect()->back()->with('success', 'Profil berhasil diupdate');
    }
    
// form ganti password
    public function formPassword()
    {
        return view('user/ganti_password');
    }

    public function updatePassword()
    {
        $model = new UserModel();
        $id = session()->get('id_user');

        $user = $model->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan');
        }

        $lama = $this->request->getPost('password_lama');
        $baru = $this->request->getPost('password_baru');
        $konf = $this->request->getPost('konfirmasi');

        // cek password lama
        if (!password_verify($lama, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama salah');
        }

        // cek konfirmasi
        if ($baru !== $konf) {
            return redirect()->back()->with('error', 'Konfirmasi tidak sama');
        }

        // update password
        $model->update($id, [
            'password' => password_hash($baru, PASSWORD_BCRYPT)
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
}