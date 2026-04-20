<?php

namespace App\Models\DataPokok;

use CodeIgniter\Model;

class StagingDataPokokModel extends Model
{
    protected $table = 'pokok_staging_data_ternak';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',

        'provinsi_id',
        'kabupaten_id',
        'jenis_ternak_id',

        'tahun',

        'populasi',

        'pemotongan_rph',
        'pemotongan_luar_rph',
        'pemotongan_tidak_tercatat',

        'produksi_susu',
        'produksi_telur',

        'status',
        'catatan_admin',
        'created_at'
    ];
}
