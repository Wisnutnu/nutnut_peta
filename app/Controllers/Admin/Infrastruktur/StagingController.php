<?php

namespace App\Controllers\Admin\Infrastruktur;

use App\Controllers\BaseController;
use App\Models\PengajuanInfrastrukturModel;

class StagingController extends BaseController
{
    public function index()
    {
        $model = new PengajuanInfrastrukturModel();
$data['list'] = $model
    ->orderBy('id', 'DESC')
    ->findAll();

        return view('admin/infrastruktur/staging_list', $data);
    }

    public function approve($id)
{
    $model = new PengajuanInfrastrukturModel();
    $db = \Config\Database::connect();

    $data = $model->find($id);

    if (!$data) {
        return redirect()->back()->with('error', 'Data tidak ditemukan');
    }

    //================== MAPPING DATA =================
    // field yang sesuai dengan database utama
    //=========================================
    
    $tables = [

        'klinikhewan' => [
            'table' => 'klinikhewan',
            'map' => [
                'provinsi'      => 'provinsi',
                'kab_kota'      => 'kab_kota',
                'nama_tempat'   => 'nama_puskeswan',
                'alamat'        => 'alamat_puskeswan',
                'latitude'      => 'latitude',
                'longitude'     => 'longitude',
            ]
        ],

        'koperasipkh' => [
            'table' => 'koperasipkh',
            'map' => [
                'provinsi'      => 'provinsi',
                'kab_kota'      => 'kab_kota',
                'nama_tempat'   => 'nama_koperasi_UPH',
                'alamat'        => 'alamat',
                'latitude'      => 'latitude',
                'longitude'     => 'longitude',
            ]
        ],

        'labbibit' => [
            'table' => 'labbibit',
            'map' => [
                'provinsi'      => 'provinsi',
                'kab_kota'      => 'kab_kota',
                'nama_tempat'   => 'nama_laboratorium',
                'alamat'        => 'alamat',
                'latitude'      => 'latitude',
                'longitude'     => 'longitude',
            ]
        ],

        'labkesmavet' => [
            'table' => 'labkesmavet',
            'map' => [
                'provinsi'      => 'provinsi',
                'kab_kota'      => 'kab_kota',
                'nama_tempat'   => 'nama_laboratorium',
                'alamat'        => 'alamat_laboratorium',
                'latitude'      => 'latitude',
                'longitude'     => 'longitude',
            ]
        ],

        'labkeswan' => [
            'table' => 'labkeswan',
            'map' => [
                'provinsi'      => 'provinsi',
                'kab_kota'      => 'kab_kota',
                'nama_tempat'   => 'nama_laboratorium',
                'alamat'        => 'alamat_laboratorium',
                'latitude'      => 'latitude',
                'longitude'     => 'longitude',
            ]
        ],

        'labpakan' => [
            'table' => 'labpakan',
            'map' => [
                'provinsi'      => 'provinsi',
                'kab_kota'      => 'kab_kota',
                'nama_tempat'   => 'nama_laboratorium',
                'alamat'        => 'alamat_laboratorium',
                'latitude'      => 'latitude',
                'longitude'     => 'longitude',
            ]
        ],

        'pasarternak' => [
            'table' => 'pasarternak',
            'map' => [
                'provinsi'      => 'provinsi',
                'kab_kota'      => 'kab_kota',
                'nama_tempat'   => 'nama_pasar_ternak',
                'alamat'        => 'alamat',
                'latitude'      => 'latitude',
                'longitude'     => 'longitude',
            ]
        ],

        'puskeswan' => [
            'table' => 'puskeswan',
            'map' => [
                'provinsi'      => 'provinsi',
                'kab_kota'      => 'kab_kota',
                'nama_tempat'   => 'nama_puskeswan',
                'alamat'        => 'alamat_puskeswan',
                'latitude'      => 'latitude',
                'longitude'     => 'longitude',
            ]
        ],

        'rph' => [
            'table' => 'rph',
            'map' => [
                'provinsi'      => 'provinsi',
                'kab_kota'      => 'kab_kota',
                'nama_tempat'   => 'nama_rph',
                'alamat'        => 'alamat_rph',
                'latitude'      => 'latitude',
                'longitude'     => 'longitude',
            ]
        ],

        'sppg' => [
            'table' => 'sppg',
            'map' => [
                'provinsi'      => 'provinsi',
                'kab_kota'      => 'kab_kota',
                'nama_tempat'   => 'Nama_SPPG',
                'alamat'        => 'alamat',
                'latitude'      => 'latitude',
                'longitude'     => 'longitude',
            ]
        ],

        'uph' => [
            'table' => 'uph',
            'map' => [
                'provinsi'      => 'provinsi',
                'kab_kota'      => 'kab_kota',
                'nama_tempat'   => 'nama_UPH',
                'alamat'        => 'alamat',
                'latitude'      => 'latitude',
                'longitude'     => 'longitude',
            ]
        ],

    ];

    $jenis = strtolower($data['jenis_infrastruktur']);

if (!isset($tables[$jenis])) {
    return redirect()->back()->with('error', 'Mapping belum tersedia');
}

$config = $tables[$jenis];
$map = $config['map'];

$dataInsert = [];

foreach ($map as $source => $target) {
    $dataInsert[$target] = $data[$source] ?? null;
}

// insert ke tabel tujuan
$db->table($config['table'])->insert($dataInsert);

// update status staging
$model->update($id, [
    'status' => 'approved',
    'verified_at' => date('Y-m-d H:i:s'),
    'verified_by' => session()->get('nama')
]);

return redirect()->back()->with('success', 'Data berhasil di-approve & masuk tabel utama');
    }
}