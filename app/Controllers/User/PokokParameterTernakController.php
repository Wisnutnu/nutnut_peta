<?php

namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Models\DataPokok\PokokParameterTernakModel;

$parameterModel->save([
    'kabupaten_id' => session()->get('kabupaten_id'),
    'jenis_ternak_id' => $this->request->getPost('jenis_ternak_id'),
    'tahun' => $this->request->getPost('tahun'),

    'berat_karkas' => $this->request->getPost('berat_karkas'),
    'berat_daging_murni' => $this->request->getPost('berat_daging_murni'),
    'berat_jeroan' => $this->request->getPost('berat_jeroan'),

    'betina_laktasi_terhadap_populasi' =>
        $this->request->getPost('betina_laktasi_terhadap_populasi'),

    'produktivitas_susu' => $this->request->getPost('produktivitas_susu'),
    'produktivitas_telur' => $this->request->getPost('produktivitas_telur'),

    'konversi_livebird_ke_karkas' =>
        $this->request->getPost('konversi_livebird_ke_karkas'),
]);
