<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    
    public function login()
    {
                //echo password_hash('1234', PASSWORD_DEFAULT);
        // die; //untuk tau hash password yang dihasilkan dari password_hash, bisa digunakan untuk membuat user baru dengan password yang sama
        return view('auth/login');
        

    }

    public function loginProcess()
{
    $session = session();
    $model = new UserModel();

    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');

    $user = $model->where('username', $username)
                  ->where('is_active', 1)
                  ->first();

    // ❌ kalau user tidak ditemukan
    if (!$user) {
        return redirect()->back()->with('error', 'Username tidak ditemukan');
    }

    // ❌ kalau password salah
    if (!password_verify($password, $user['password'])) {
        return redirect()->back()->with('error', 'Password salah');
    }

    // ✅ kalau login berhasil
    $data = [
    'id_user'      => $user['id'],
    'nama'         => $user['nama'],
    'role'         => $user['role'],
    'level_user' => $user['level_user'],
    'provinsi_id'  => $user['provinsi_id'] ?? null,
    'kabupaten_id' => $user['kabupaten_id'] ?? null,
    'kecamatan_id' => $user['kecamatan_id'] ?? null,
    'provinsi'     => $user['provinsi'] ?? null,
    'kab_kota'     => $user['kab_kota'] ?? null,
    'logged_in'    => true
];

    $session->set($data);
    $session->regenerate();

    if ($user['role'] == 'master') {
        return redirect()->to('/master');
    } elseif ($user['role'] == 'admin') {
        return redirect()->to('/admin');
    } else {
        return redirect()->to('/user/dashboard');
    }
}
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Logout berhasil');
    }
}
