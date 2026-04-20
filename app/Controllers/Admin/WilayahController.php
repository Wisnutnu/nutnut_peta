<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ManagementUser\KabupatenModel;
use App\Models\ManagementUser\KecamatanModel;

class WilayahController extends BaseController
{
    public function kabupaten($provinsi_id)
    {
        $model = new KabupatenModel();
        $data = $model->where('provinsi_id', $provinsi_id)->findAll();

        echo '<option value="">Pilih Kabupaten</option>';
        foreach ($data as $k) {
            echo "<option value='{$k['id']}'>{$k['nama_kabupaten']}</option>";
        }
    }

    public function kecamatan($kabupaten_id)
    {
        $model = new KecamatanModel();
        $data = $model->where('kabupaten_id', $kabupaten_id)->findAll();

        echo '<option value="">Pilih Kecamatan</option>';
        foreach ($data as $k) {
            echo "<option value='{$k['id']}'>{$k['nama_kecamatan']}</option>";
        }
    }
}