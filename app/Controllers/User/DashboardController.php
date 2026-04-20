<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $level = session()->get('level_user');
        $provinsi_id = session()->get('provinsi_id');
        $kabupaten_id = session()->get('kabupaten_id');
        $kecamatan_id = session()->get('kecamatan_id');

        $data['rekap'] = 0;

        // 🔵 PROVINSI
        if ($level == 'provinsi') {

            $data['rekap'] = $db->table('populasi_penyuluh')
                ->where('provinsi_id', $provinsi_id)
                ->where('status', 'disetujui')
                ->countAllResults();

            $data['judul'] = 'Dashboard Provinsi';
            $data['desc'] = 'Total data disetujui per provinsi';

        }

        // 🟢 KABUPATEN
        elseif ($level == 'kabupaten') {

            $data['rekap'] = $db->table('populasi_penyuluh')
                ->where('kabupaten_id', $kabupaten_id)
                ->where('status', 'disetujui')
                ->countAllResults();

            $data['judul'] = 'Dashboard Kabupaten';
            $data['desc'] = 'Total data disetujui per kabupaten';

        }

        // 🟡 KECAMATAN
        else {

            $data['rekap'] = $db->table('populasi_penyuluh')
                ->where('kecamatan_id', $kecamatan_id)
                ->where('status', 'disetujui')
                ->countAllResults();

            $data['judul'] = 'Dashboard Kecamatan';
            $data['desc'] = 'Total data disetujui di wilayah anda';
        }

        return view('User/dashboard', $data);
    }
}