<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\DataPokok\StagingDataPokokModel;
use App\Models\DataPokok\JenisTernakModel;
use App\Models\DataPokok\PokokParameterTernakModel;
use App\Services\DataPokokCalculator;
use Config\Database;

class DataPokokController extends BaseController
{
    protected $db;
    protected $stagingModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->stagingModel = new StagingDataPokokModel();
    }

    public function index()
{
    $provinsi = session()->get('provinsi');
    $kab_kota = session()->get('kab_kota');

    $jenisModel = new JenisTernakModel();
    $jenisTernak = $jenisModel->findAll();

    $riwayat = $this->stagingModel
        ->where('user_id', session()->get('user_id'))
        ->orderBy('created_at', 'DESC')
        ->findAll();

    return view('user/datapokok/index', [
        'provinsi' => $provinsi,
        'kab_kota' => $kab_kota,
        'jenisTernak' => $jenisTernak,
        'riwayat' => $riwayat,
    ]);
}


public function store()
{

    $mode = $this->request->getPost('mode_inputan');

    $input = [
        'pemotongan_di_RPH_TPH' => str_replace('.', '', $this->request->getPost('pemotongan_rph') ?? 0),
        'pemotongan_diluar_RPH_TPH' => str_replace('.', '', $this->request->getPost('pemotongan_luar_rph') ?? 0),
        'pemotongan_tidak_tercatat' => str_replace('.', '', $this->request->getPost('pemotongan_tidak_tercatat') ?? 0)
    ];

    $parameter = [
    'berat_hidup' => (float) str_replace('.', '', $this->request->getPost('berat_hidup') ?? 0),
    'berat_karkas' => (float) str_replace('.', '', $this->request->getPost('berat_karkas') ?? 0),
    'berat_daging_murni' => (float) str_replace('.', '', $this->request->getPost('berat_daging_murni') ?? 0),
    'berat_jeroan' => (float) str_replace('.', '', $this->request->getPost('berat_jeroan') ?? 0),
    'berat_daging_variasi' => (float) str_replace('.', '', $this->request->getPost('berat_daging_variasi') ?? 0),
    'persentase_berat_daging_variasi' => (float) str_replace('.', '', $this->request->getPost('persentase_berat_daging_variasi') ?? 0),
    'pemotongan_tidak_tercatat_persen' => (float) str_replace('.', '', $this->request->getPost('pemotongan_tidak_tercatat_persen') ?? 0)
];

    $hasil = DataPokokCalculator::hitungPotongBesar($input, $parameter);

    $parameterModel = new PokokParameterTernakModel();
    $cek = $parameterModel
    ->where('kabupaten_id', session()->get('kabupaten_id'))
    ->where('jenis_ternak_id', $this->request->getPost('jenis_ternak_id'))
    ->where('tahun', $this->request->getPost('tahun'))
    ->first();

    $parameterModel->insert([

        'kabupaten_id' => session()->get('kabupaten_id'),
        'jenis_ternak_id' => $this->request->getPost('jenis_ternak_id'),
        'tahun' => $this->request->getPost('tahun'),

        'berat_karkas' => $parameter['berat_karkas'],
        'berat_daging_murni' => $parameter['berat_daging_murni'],
        'berat_jeroan' => $parameter['berat_jeroan'],

        'created_at' => date('Y-m-d H:i:s')

    ]);

    $this->stagingModel->insert([

        'user_id' => session()->get('user_id'),
        'provinsi_id' => session()->get('provinsi_id'),
        'kabupaten_id' => session()->get('kabupaten_id'),

        'jenis_ternak_id' => $this->request->getPost('jenis_ternak_id'),
        'tahun' => $this->request->getPost('tahun'),

        'mode_inputan' => $mode,

        'populasi' => str_replace('.', '', $this->request->getPost('populasi') ?? 0),

        'pemotongan_rph' => $input['pemotongan_di_RPH_TPH'],
        'pemotongan_luar_rph' => $input['pemotongan_diluar_RPH_TPH'],
        'pemotongan_tidak_tercatat' => $input['pemotongan_tidak_tercatat'],

        'produksi_daging' => $hasil['dagingmurni_jeroan_daging_variasi'],

        'status' => 'pending',
        'created_at' => date('Y-m-d H:i:s')
    ]);

    

    return redirect()->back()->with(
        'success',
        '✅ Data Pokok berhasil dikirim dan menunggu verifikasi admin.'
    );
}

}
