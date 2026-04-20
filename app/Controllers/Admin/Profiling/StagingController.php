<?php

namespace App\Controllers\Admin\Profiling;

use App\Controllers\BaseController;
use App\Models\UploadStagingModel;
use Config\Database;
use App\Models\PengajuanInfrastrukturModel;

class StagingController extends BaseController
{
    // ================= LIST DATA =================
public function index()
{
    
    $model = new UploadStagingModel();
    $infraModel = new PengajuanInfrastrukturModel();

    $builder = $model
        ->select('upload_staging.*, users.kab_kota')
        ->join('users', 'users.id = upload_staging.user_id', 'left');

    if ($kab = $this->request->getGet('kabupaten')) {
        $builder->like('users.kab_kota', $kab);
    }

    if ($kat = $this->request->getGet('kategori')) {
        $builder->where('upload_staging.kategori', $kat);
    }

    $status = $this->request->getGet('status');

    if ($status === 'all') {
        // tampil semua
    } elseif ($status) {
        $builder->where('upload_staging.status', $status);
    }

    if ($tgl = $this->request->getGet('tanggal')) {
        $builder->where('DATE(upload_staging.created_at)', $tgl);
    }

    if (!$this->request->getGet('status')) {
        $builder->where('upload_staging.status !=', 'approved');
    }

    $list = $builder
        ->orderBy('upload_staging.created_at', 'DESC')
        ->findAll();

// bagian filter
    $infraBuilder = $infraModel;

// filter kabupaten (kalau ada fieldnya, misal kab_kota atau alamat)
if ($kab = $this->request->getGet('kabupaten')) {
    $infraBuilder->like('alamat', $kab); // atau 'kab_kota'
}

// filter status
$status = $this->request->getGet('status');

if ($status && $status != 'all') {
    $infraBuilder->where('status', $status);
}

// filter tanggal
if ($tgl = $this->request->getGet('tanggal')) {
    $infraBuilder->where('DATE(created_at)', $tgl);
}

$infrastruktur = $infraBuilder
    ->orderBy('id', 'DESC')
    ->findAll();

    $data = [
        'list' => $list,
        'kategoriList' => $model->select('kategori')->groupBy('kategori')->findAll(),
        'infrastruktur' => $infrastruktur
    ];

    return view('admin/profiling_staging_list', $data);
    $db = \Config\Database::connect();
}

    // ================= PREVIEW DATA =================
 public function preview($id)
{
    $staging = new UploadStagingModel();

    $row = $staging->find($id);

    if (!$row) {
        return $this->response->setJSON([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }

    return $this->response->setJSON([
        'status' => true,
        'id' => $row['id'],
        'kategori' => $row['kategori'],
        'jumlah_row' => $row['jumlah_row'],
        'data' => json_decode($row['data_json'], true),
        'catatan_admin' => $row['catatan_admin'] ?? null
    ]);
}


    // ================= APPROVE =================
public function approve($id)
{
    $stagingModel = new UploadStagingModel();
    $db = Database::connect();

    $row = $stagingModel->find($id);

    if (!$row) {
        return $this->response->setJSON([
            'status' => false,
            'message' => 'Data staging tidak ditemukan'
        ]);
    }

    if ($row['status'] !== 'pending') {
        return $this->response->setJSON([
            'status' => false,
            'message' => 'Data sudah diproses'
        ]);
    }

    $data = json_decode($row['data_json'], true);
    $kategori = $row['kategori'];

    $db->transStart();

    foreach ($data as $item) {
        switch ($kategori) {

            case 'populasi':
                $db->table('populasi')->insert([
                    'provinsi'        => $item[1],
                    'kab_kota'        => $item[2],
                    'jenis_ternak'    => $item[3],
                    'tahun'           => $item[4],
                    'jumlah_populasi' => $item[5],
                ]);
                break;

            case 'produksi':
                $db->table('produksi')->insert([
                    'provinsi'       => $item[1],
                    'kab_kota'       => $item[2],
                    'jenis_produksi' => $item[3],
                    'tahun'          => $item[4],
                    'jumlah'         => $item[5],
                ]);
                break;

            case 'harga':
                if (!isset($item[6])) {
                throw new \Exception('Data harga tidak lengkap (kategori / harga kosong)');
                }
                $db->table('harga')->insert([
                    'provinsi'     => $item[1],
                    'kab_kota'     => $item[2],
                    'jenis_ternak' => $item[3],
                    'kategori'     => $item[4],
                    'tahun'        => $item[5],
                    'harga'        => $item[6],
                ]);
                break;

            default:
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Kategori tidak dikenali'
                ]);
        }
    }

    $stagingModel->update($id, [
        'status' => 'approved'
    ]);

    $db->transComplete();

    if ($db->transStatus() === false) {
        return $this->response->setJSON([
            'status' => false,
            'message' => 'Gagal insert data'
        ]);
    }

    return $this->response->setJSON([
        'status' => true,
        'message' => 'Data berhasil di-approve'
    ]);
}

    // ================= REJECT =================
public function reject($id)
{
    $stagingModel = new UploadStagingModel();
    $payload = $this->request->getJSON(true);

    $catatan = $payload['catatan_admin'] ?? null;

    if (!$catatan) {
        return $this->response->setJSON([
            'status' => false,
            'message' => 'Catatan wajib diisi saat reject.'
        ]);
    }

    $stagingModel->update($id, [
        'status' => 'rejected',
        'catatan_admin' => $catatan
    ]);

    return $this->response->setJSON([
        'status' => true,
        'message' => 'Data berhasil direject'
    ]);
}


    // =================== DOWNLOAD ========================
public function download($id)
{
    $staging = new UploadStagingModel();

    $row = $staging->find($id);

    if (!$row) {
        die('DATA STAGING TIDAK DITEMUKAN');
    }

    if (empty($row['file_path'])) {
        die('FILE_PATH KOSONG DI DATABASE');
    }

    $filepath = WRITEPATH . 'uploads/' . $row['file_path'];

    if (!file_exists($filepath)) {
        die('FILE TIDAK ADA DI SERVER: ' . $filepath);
    }

    return $this->response->download($filepath, null)
                          ->setFileName($row['file_name']);


$pengajuan = $this->pengajuanModel->find($id);

switch ($pengajuan['jenis_infrastruktur']) {

    case 'rph':
        $dataInsert = [
            'nama_rph' => $pengajuan['nama_tempat'],
            'alamat' => $pengajuan['alamat'],
            'latitude' => $pengajuan['latitude'],
            'longitude' => $pengajuan['longitude']
        ];

        $db->table('rph')->insert($dataInsert);
    break;

}

}


// ================= APPROVE INFRASTRUKTUR =================
public function approveInfrastruktur($id)
{
    $model = new PengajuanInfrastrukturModel();

    $model->update($id, [
        'status' => 'approved',
        'verified_at' => date('Y-m-d H:i:s'),
        'verified_by' => session()->get('nama')
    ]);

    return $this->response->setJSON([
        'status' => 'success',
        'message' => 'Data disetujui'
    ]);
}

public function rejectInfrastruktur($id)
{
    $model = new PengajuanInfrastrukturModel();

    $model->update($id, [
        'status' => 'rejected',
        'keterangan' => $this->request->getJSON()->catatan_admin ?? '',
        'verified_at' => date('Y-m-d H:i:s'),
        'verified_by' => session()->get('nama')
    ]);

    return $this->response->setJSON([
        'status' => 'success',
        'message' => 'Data ditolak'
    ]);
}

}
