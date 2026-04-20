<?php

namespace App\Controllers\User\Penyuluh;

use App\Controllers\BaseController;
use App\Models\Penyuluh\PopulasiPenyuluhModel;
use App\Models\DataPokok\JenisTernakModel;

class InputPopulasiController extends BaseController
{
    protected $populasi;
    protected $jenisTernak;

    public function __construct()
    {
        // 🔐 Cek hanya user kecamatan
            if (!session()->get('logged_in')) {
                redirect()->to('/login')->send();
                exit;
            }

            if (session()->get('kecamatan_id') == null) {
                exit('Akses hanya untuk penyuluh kecamatan');
            }
        $this->populasi = new PopulasiPenyuluhModel();
        $this->jenisTernak = new JenisTernakModel();
    }

    // =========================
    // INDEX (LIST DATA)
    // =========================
    public function index()
    {
        $data['populasi'] = $this->populasi
            ->select('populasi_penyuluh.*, pokok_master_jenis_ternak.nama_jenis')
            ->join('pokok_master_jenis_ternak', 'pokok_master_jenis_ternak.id = populasi_penyuluh.jenis_ternak_id')
            ->where('populasi_penyuluh.kecamatan_id', session()->get('kecamatan_id'))
            ->findAll();

        return view('user/penyuluh/populasi/index', $data);
    }

    // =========================
    // FORM INPUT
    // =========================
    public function create()
    {
        $data['jenis_ternak'] = $this->jenisTernak->findAll();

        return view('user/penyuluh/populasi/form', $data);
    }

    // =========================
    // SIMPAN DATA
    // =========================
    public function store()
    {
        if (!$this->validate([
            'nama_pemilik_ternak' => 'required',
            'jenis_ternak_id'     => 'required',
            'jumlah'              => 'required|numeric',
            'tahun'               => 'required|numeric'
        ])) {
            return redirect()->back()->withInput();
        }

        $this->populasi->save([
            'id_user'              => session()->get('id_user'),

            'provinsi_id'          => session()->get('provinsi_id'),
            'kabupaten_id'         => session()->get('kabupaten_id'),
            'kecamatan_id'         => session()->get('kecamatan_id'),

            'nama_pemilik_ternak'  => $this->request->getPost('nama_pemilik_ternak'),
            'no_telp'              => $this->request->getPost('no_telp'),

            'jenis_ternak_id'      => $this->request->getPost('jenis_ternak_id'),

            'latitude'             => $this->request->getPost('latitude'),
            'longitude'            => $this->request->getPost('longitude'),

            'jumlah'               => $this->request->getPost('jumlah'),
            'tahun'                => $this->request->getPost('tahun'),

            'status'               => 'diajukan'
        ]);

        return redirect()->to('/user/populasi')
            ->with('success', 'Data berhasil disimpan');
    }

    // =========================
    // EDIT
    // =========================
    public function edit($id)
    {
        $data['populasi'] = $this->populasi
            ->where('id', $id)
            ->where('kecamatan_id', session()->get('kecamatan_id'))
            ->first();

        if (!$data['populasi']) {
            return redirect()->to('/user/populasi')->with('error', 'Data tidak ditemukan');
        }
        if ($data['populasi']['status'] == 'diajukan' || $data['populasi']['status'] == 'disetujui') {
            return redirect()->to('/user/populasi')->with('error', 'Data sudah dikunci');
        }

        $data['jenis_ternak'] = $this->jenisTernak->findAll();

        return view('user/penyuluh/populasi/form', $data);
    }

    // =========================
    // UPDATE
    // =========================
    public function update($id)
    {
        $cek = $this->populasi
            ->where('id', $id)
            ->where('kecamatan_id', session()->get('kecamatan_id'))
            ->first();

        if (!$cek) {
            return redirect()->to('/user/populasi')->with('error', 'Data tidak ditemukan');
        }

        $this->populasi->update($id, [
            'nama_pemilik_ternak' => $this->request->getPost('nama_pemilik_ternak'),
            'no_telp'             => $this->request->getPost('no_telp'),
            'jenis_ternak_id'     => $this->request->getPost('jenis_ternak_id'),
            'latitude'            => $this->request->getPost('latitude'),
            'longitude'           => $this->request->getPost('longitude'),
            'jumlah'              => $this->request->getPost('jumlah'),
            'tahun'               => $this->request->getPost('tahun'),
        ]);

        return redirect()->to('/user/populasi')->with('success', 'Data berhasil diupdate');
    }

    // ========================
    // KIRIM DATA KE KABUPATEN
    // ========================
    public function kirim($id)
    {
        $data = $this->populasi
            ->where('id', $id)
            ->where('kecamatan_id', session()->get('kecamatan_id'))
            ->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // hanya draft & ditolak yang boleh kirim
        if (!in_array($data['status'], ['draft', 'ditolak'])) {
            return redirect()->back()->with('error', 'Data tidak bisa dikirim');
        }

        $this->populasi->update($id, [
            'status' => 'diajukan'
        ]);

        return redirect()->back()->with('success', 'Data berhasil dikirim ke kabupaten');
    }
    // =========================
    // DELETE
    // =========================
    public function delete($id)
    {
        $cek = $this->populasi
            ->where('id', $id)
            ->where('kecamatan_id', session()->get('kecamatan_id'))
            ->first();

        if (!$cek) {
            return redirect()->to('/user/populasi')->with('error', 'Data tidak ditemukan');
        }

        $this->populasi->delete($id);

        return redirect()->to('/user/populasi')->with('success', 'Data berhasil dihapus');
    }

}