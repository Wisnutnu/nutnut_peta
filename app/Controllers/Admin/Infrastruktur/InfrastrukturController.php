<?php
// InfrastrukturController = READ (tampilan + filter)

namespace App\Controllers\Admin\Infrastruktur;

use App\Controllers\BaseController;
use App\Models\PengajuanInfrastrukturModel;

class InfrastrukturController extends BaseController
{
    public function index()
{
    $model = new PengajuanInfrastrukturModel();
    $builder = $model;

    // kabupaten (sementara pakai alamat)
    if ($kab = $this->request->getGet('kabupaten')) {
        $builder->like('alamat', $kab);
    }

    // status
    if ($status = $this->request->getGet('status')) {
        if ($status != 'all') {
            $builder->where('status', $status);
        }
    }

    // tanggal (hanya kalau tidak kosong)
    if ($tgl = $this->request->getGet('tanggal')) {
        $builder->where('created_at >=', $tgl . ' 00:00:00');
        $builder->where('created_at <=', $tgl . ' 23:59:59');
    }

    $data['infrastruktur'] = $builder
        ->orderBy('id', 'DESC')
        ->findAll();

    return view('admin/infrastruktur_staging_list', $data);
} 

}

