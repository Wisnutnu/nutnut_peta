<?php

namespace App\Controllers\Admin\Infrastruktur;

use App\Controllers\BaseController;
use App\Models\PengajuanInfrastrukturModel;

class ApprovalInfrastrukturController extends BaseController
{
    public function index()
    {
        $model = new PengajuanInfrastrukturModel();

        $data['infrastruktur'] = $model
            ->where('status', 'approved')
            ->orderBy('verified_at', 'DESC')
            ->findAll();

        return view('admin/approval_infrastruktur', $data);
    }
}