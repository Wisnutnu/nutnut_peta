<?php
namespace App\Controllers\User;

use App\Controllers\BaseController;
use Config\Database;
use App\Models\UploadStagingModel;
use App\Models\UploadInfrastrukturModel;

class UploadController extends BaseController
{
    protected $db;
    protected $uploadStagingModel;

    public function __construct()
    {
        $this->db = Database::connect(); //  INI WAJIB
    }
// menampilkan form upload
    public function index()
    {
        // 🔹 MASTER JENIS TERNAK (sementara hard-code)
        $jenisTernak = [
            'Sapi Potong',
            'Sapi Perah',
            'Kerbau',
            'Kambing',
            'Domba',
            'Kuda',
            'Babi',
            'Ayam Buras',
            'Ayam Ras Pedaging',
            'Ayam Ras Petelur',
            'Itik',
            'Itik Manila',
            'Puyuh',
            'Kelinci'
        ];

        // 🔹 MASTER JENIS PRODUKSI
        $jenisProduksi = [
            'Daging Sapi',
            'Daging Kerbau',
            'Daging Kambing',
            'Daging Domba',
            'Daging Kuda',
            'Daging Kelinci',
            'Daging Babi',
            'Daging Ayam Buras',
            'Daging Ayam Ras Pedaging',
            'Daging Ayam Ras Petelur',
            'Daging Itik',
            'Daging Itik Manila',
            'Daging Puyuh',
            'Susu Sapi',
            'Susu Kambing',
            'Susu Kerbau',
            'Telur Ayam Ras',
            'Telur Ayam Buras',
            'Telur Itik',
            'Telur Itik Manila',
            'Telur Puyuh',
            'Daging Sapi Lokal',
            'Daging Sapi ex-Impor'
        ];

        $jenisharga = [ 
             'Daging Sapi',
            'Daging Ayam Ras',
            'Telur Ayam Ras',
            'Sapi',
            'Ayam Ras',
            'Telur Ayam Ras'
        ];

        
    // 🔹 WILAYAH DARI LOGIN USER
    $provinsi = session()->get('provinsi');
    $kab_kota = session()->get('kab_kota');
        $model = new UploadStagingModel();

$riwayat = $model
    ->where('user_id', session()->get('user_id'))
    ->orderBy('created_at', 'DESC')
    ->paginate(10);

$pager = $model->pager;

// proses decode data_json untuk ditampilkan ringkas di tabel riwayat
foreach ($riwayat as &$r) {

    $data = json_decode($r['data_json'], true);

    // jaga-jaga kalau json kosong / rusak
    if (!$data || !isset($data[0])) {
        $r['detail_data'] = '-';
        continue;
    }

    $item = $data[0]; // karena input user 1 baris

    switch ($r['kategori']) {

        case 'populasi':
            // [null, prov, kab, jenis_ternak, tahun, jumlah]
            $r['detail_data'] = $item[3] . ' (' . $item[4] . ')';
            break;

        case 'produksi':
            // [null, prov, kab, jenis_produksi, tahun, jumlah]
            $r['detail_data'] = $item[3];
            break;

        case 'harga':
            // [null, prov, kab, jenis_ternak, kategori, tahun, harga]
            $r['detail_data'] = $item[3];
            break;

        default:
            $r['detail_data'] = '-';
    }
}
// ================= Upload infrastruktur =================
$infraModel = new UploadInfrastrukturModel();

$riwayatinfrastruktur = $infraModel
    ->where('user_id', session()->get('user_id'))
    ->orderBy('id', 'DESC')
    ->findAll();
// =========
$data = [
        'provinsi'      => $provinsi,
        'kab_kota'      => $kab_kota,
        'jenisTernak'   => $jenisTernak,
        'jenisProduksi' => $jenisProduksi,
        'jenisharga'    => $jenisharga,
        'riwayat'       => $riwayat,
        'pager'         => $pager,
        'riwayatinfrastruktur' => $riwayatinfrastruktur
    ];

    return view('user/upload', $data);
}

 
public function storePopulasi()
{
    $db = Database::connect();

    $provinsi = $this->request->getPost('provinsi');
    $kab      = $this->request->getPost('kab_kota');
    $jenis    = $this->request->getPost('jenis_ternak');
    $tahun    = $this->request->getPost('tahun');
    $jumlah   = str_replace('.', '', $this->request->getPost('jumlah_populasi'));

    // ✅ CEK DUPLIKAT DULU
    $cek = $db->table('upload_staging')
        ->where([
            'user_id'  => session()->get('user_id'),
            'kategori' => 'populasi',
            'provinsi' => $provinsi,
            'kab_kota' => $kab,
            'jenis'    => $jenis,
            'tahun'    => $tahun
        ])
        ->whereIn('status', ['pending','approved'])
        ->countAllResults();

    if ($cek > 0) {
        return redirect()->back()->with(
            'error',
            '❌ Data populasi untuk kombinasi tersebut sudah pernah dikirim dan sedang diproses / sudah disetujui.'
        );
    }

    // ✅ BARU INSERT KE STAGING
    $row = [null, $provinsi, $kab, $jenis, $tahun, $jumlah];

    $db->table('upload_staging')->insert([
        'user_id'    => session()->get('user_id'),
        'kategori'   => 'populasi',
        'provinsi'   => $provinsi,
        'kab_kota'   => $kab,
        'jenis'      => $jenis,
        'tahun'      => $tahun,
        'source'     => 'user',
        'data_json'  => json_encode([$row]),
        'jumlah_row' => 1,
        'status'     => 'pending',
        'created_at' => date('Y-m-d H:i:s'),
        'file_name'  => 'FORM_INPUT_USER'
    ]);

    return redirect()->back()->with(
        'success',
        '✅ Data Populasi berhasil dikirim dan menunggu verifikasi admin.'
    );
}



public function storeProduksi()
{
    $db = Database::connect();
    // format baris (MENYERUPAI EXCEL)
    $provinsi = $this->request->getPost('provinsi');
    $kab      = $this->request->getPost('kab_kota');
    $jenis    = $this->request->getPost('jenis_produksi');
    $tahun    = $this->request->getPost('tahun');
    $jumlah = str_replace('.', '', $this->request->getPost('jumlah'));

    // ✅ CEK DUPLIKAT DULU
    $cek = $db->table('upload_staging')
        ->where([
            'user_id'  => session()->get('user_id'),
            'kategori' => 'produksi',
            'provinsi' => $provinsi,
            'kab_kota' => $kab,
            'jenis'    => $jenis,
            'tahun'    => $tahun
        ])
        ->whereIn('status', ['pending','approved'])
        ->countAllResults();

    if ($cek > 0) {
        return redirect()->back()->with(
            'error',
            '❌ Data produksi untuk kombinasi tersebut sudah pernah dikirim dan sedang diproses / sudah disetujui.'
        );
    }

    // ✅ BARU INSERT KE STAGING
    $row = [null, $provinsi, $kab, $jenis, $tahun, $jumlah];

    $db->table('upload_staging')->insert([
        'user_id'    => session()->get('user_id'),
        'kategori'   => 'produksi',
        'provinsi'   => $provinsi,
        'kab_kota'   => $kab,
        'jenis'      => $jenis,
        'tahun'      => $tahun,
        'source'     => 'user',
        'data_json'  => json_encode([$row]),
        'jumlah_row' => 1,
        'status'     => 'pending',
        'created_at' => date('Y-m-d H:i:s'),
        'file_name'  => 'FORM_INPUT_USER'
    ]);

    return redirect()->back()->with(
        'success',
        '✅ Data Produksi berhasil dikirim dan menunggu verifikasi admin.'
    );
}


public function storeharga()
    {
        $db = Database::connect();

        $provinsi = $this->request->getPost('provinsi');
        $kab      = $this->request->getPost('kab_kota');
        $jenis    = $this->request->getPost('jenis_ternak');
        $kategori = $this->request->getPost('kategori'); // produsen / konsumen
        $tahun    = $this->request->getPost('tahun');
        $harga    = str_replace('.', '', $this->request->getPost('harga')); // ⬅️ FIX

        // ✅ CEK DUPLIKAT DULU
        $cek = $db->table('upload_staging')
            ->where([
                'user_id'  => session()->get('user_id'),
                'kategori' => 'harga',
                'provinsi' => $provinsi,
                'kab_kota' => $kab,
                'jenis'    => $jenis,
                'tahun'    => $tahun,
                
            ])
            ->whereIn('status', ['pending','approved'])
            ->countAllResults();

        if ($cek > 0) {
            return redirect()->back()->with(
                'error',
                '❌ Data harga untuk kombinasi tersebut sudah pernah dikirim dan sedang diproses / sudah disetujui.'
            );
        }
        

        // ✅ BARU INSERT KE STAGING
        $row = [null, $provinsi, $kab, $kategori, $jenis, $tahun, $harga];

        $db->table('upload_staging')->insert([
            'user_id'    => session()->get('user_id'),
            'kategori'   => 'harga',
            'provinsi'   => $provinsi,
            'kab_kota'   => $kab,
            'jenis'      => $jenis,
            'tahun'      => $tahun,
            'source'     => 'user',
            'data_json'  => json_encode([$row]),
            'jumlah_row' => 1,
            'status'     => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'file_name'  => 'FORM_INPUT_USER'
        ]);

        return redirect()->back()->with(
            'success',
            '✅ Data Harga berhasil dikirim dan menunggu verifikasi admin.'
        );
    }

// ================= Upload infrastruktur =================

public function saveInfrastruktur()
{
    $model = new UploadInfrastrukturModel();

    $data = [
    'user_id' => session()->get('user_id'),
    'jenis_infrastruktur' => $this->request->getPost('jenis_infrastruktur'),
    'nama_tempat' => $this->request->getPost('nama'),
    'alamat' => $this->request->getPost('alamat'),
    'latitude' => $this->request->getPost('latitude'),
    'longitude' => $this->request->getPost('longitude'),
    'provinsi' => session()->get('provinsi'),
    'kab_kota' => session()->get('kab_kota'),

    'status' => 'pending',
    'created_at' => date('Y-m-d H:i:s') // sekalian rapihin
];

    $model->insert($data);

    return redirect()->to('/user/upload#infrastruktur')
                 ->with('success', 'Data berhasil dikirim');
}

public function updateInfrastruktur($id)
{
    $model = new UploadInfrastrukturModel();

    $dataLama = $model->find($id);

    if (!$dataLama || $dataLama['status'] != 'pending') {
        return redirect()->back()->with('error', 'Data tidak bisa diupdate');
    }

    $model->update($id, [
        'jenis_infrastruktur' => $this->request->getPost('jenis_infrastruktur'),
        'nama_tempat' => $this->request->getPost('nama'),
        'alamat' => $this->request->getPost('alamat'),
        'latitude' => $this->request->getPost('latitude'),
        'longitude' => $this->request->getPost('longitude'),
    ]);

    return redirect()->to('/user/upload#infrastruktur')
        ->with('success', 'Data berhasil diupdate');
}

public function editInfrastruktur($id)
{
    $model = new UploadInfrastrukturModel();

    $data = $model->find($id);

    if (!$data || $data['status'] != 'pending') {
        return redirect()->back()->with('error', 'Data tidak bisa diedit');
    }

    return view('user/edit_infrastruktur', ['data' => $data]);
}

public function deleteInfrastruktur($id)
{
    $model = new UploadInfrastrukturModel();

    $data = $model->find($id);

    if (!$data || $data['status'] != 'pending') {
        return redirect()->back()->with('error', 'Data tidak bisa dihapus');
    }

    $model->delete($id);

    return redirect()->to('/user/upload#infrastruktur')
        ->with('success', 'Data berhasil dihapus');
}

}
