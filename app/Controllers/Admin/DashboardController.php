<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UploadInfrastrukturModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $infraModel = new UploadInfrastrukturModel();
        $userModel  = new UserModel();

        // =======================
        // STATISTIK STATUS
        // =======================

        $data['pending']  = $infraModel->where('status', 'pending')->countAllResults();
        $data['approved'] = $infraModel->where('status', 'approved')->countAllResults();
        $data['rejected'] = $infraModel->where('status', 'rejected')->countAllResults();

        // =======================
        // TOTAL DATA
        // =======================

        $data['totalInfra'] = $infraModel->countAll();
        $data['totalUser']  = $userModel->countAll();

        // =======================
        // PENDING HARI INI
        // (jika ada created_at)
        // =======================

        if ($infraModel->db->fieldExists('created_at', 'pengajuan_infrastruktur')) {
            $data['pendingToday'] = $infraModel
                ->where('status', 'pending')
                ->where('DATE(created_at)', date('Y-m-d'))
                ->countAllResults();
        } else {
            $data['pendingToday'] = $data['pending'];
        }

        // =======================
        // TOP JENIS INFRASTRUKTUR
        // =======================

        $data['topJenis'] = $infraModel
            ->select('jenis_infrastruktur, COUNT(*) as total')
            ->groupBy('jenis_infrastruktur')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->findAll();

        // =======================
        // DATA CHART STATUS
        // =======================

        $data['chartStatus'] = [
            $data['pending'],
            $data['approved'],
            $data['rejected']
        ];

        return view('admin/dashboard', $data);
    }
}