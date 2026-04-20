<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class InfrastrukturDataTersimpanController extends BaseController
{
    public function index()
{
    $db = \Config\Database::connect();
    $session = session();

    $provinsi_id = $session->get('provinsi_id');
    $kabupaten_id = $session->get('kabupaten_id');
    $role = $session->get('role');

    // 🔵 SPPG
    $builder = $db->table('sppg');
    if ($role == 'user') {
        $builder->where('kabupaten_id', $kabupaten_id);
    } elseif ($role == 'admin') {
        $builder->where('provinsi_id', $provinsi_id);
    }
    $data['sppg'] = $builder->get()->getResultArray();

    // 🔵 SPPG NOTAGING
    $builder = $db->table('sppgnotaging');
    if ($role == 'user') {
        $builder->where('kabupaten_id', $kabupaten_id);
    } elseif ($role == 'admin') {
        $builder->where('provinsi_id', $provinsi_id);
    }
    $data['sppgnotaging'] = $builder->get()->getResultArray();

    // 🔵 KLINIK HEWAN (contoh)
    $builder = $db->table('klinikhewan');
    if ($role == 'user') {
        $builder->where('kabupaten_id', $kabupaten_id);
    } elseif ($role == 'admin') {
        $builder->where('provinsi_id', $provinsi_id);
    }
    $data['klinikhewan'] = $builder->get()->getResultArray();

    $tables = [
            // 'klinikhewan' => ['nama_klinik', 'alamat_klinik', 'latitude', 'longitude'],
            // 'koperasipkh' => ['nama_koperasi', 'alamat', 'lat', 'lng'],
            // 'labbibit'    => ['nama_laboratorium', 'alamat', 'latitude', 'longitude'],
            // 'labkesmavet' => ['nama_laboratorium', 'alamat', 'latitude', 'longitude'],
            // 'labkeswan'   => ['nama_laboratorium', 'alamat', 'latitude', 'longitude'],
            // 'labpakan'    => ['nama_pabrik', 'alamat', 'latitude', 'longitude'],
            // 'pasarternak' => ['nama_pasar', 'alamat', 'latitude', 'longitude'],
            // 'puskeswan'   => ['nama_puskeswan', 'alamat', 'latitude', 'longitude'],
            // 'rph'         => ['nama_rph', 'alamat', 'latitude', 'longitude'],
            // 'uph'         => ['nama_UPH', 'alamat', 'latitude', 'longitude'],
            // 'sppg'        => ['nama', 'alamat', 'latitude', 'longitude'],
            'sppgnotaging'        => ['nama_sppg', 'alamat_sppg'],
        ];

    $dataGabungan = [];

    foreach ($tables as $table => $fields) {

        $builder = $db->table($table);

        // 🔐 filter wilayah
        if ($role == 'user') {
            $builder->where('kabupaten_id', $kabupaten_id);
        } elseif ($role == 'admin') {
            $builder->where('provinsi_id', $provinsi_id);
        }

        $query = $builder->get()->getResultArray();

        foreach ($query as $row) {
            $dataGabungan[] = [
                'jenis'   => $table,
                'nama_sppg'    => $row[$fields[0]] ?? '-',
                'alamat_sppg'  => $row[$fields[1]] ?? '-',
                'kabkota_sppg' => $row['kabkota_sppg'] ?? '-',
            ];
        }
    }

    $data['infrastruktur'] = $dataGabungan;

    return view('user/infrastrukturdatatersimpan', $data);
}
}