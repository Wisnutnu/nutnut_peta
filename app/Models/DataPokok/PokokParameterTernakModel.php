<?php

namespace App\Models\DataPokok;

use CodeIgniter\Model;

class PokokParameterTernakModel extends Model
{
    protected $table = 'pokok_parameter_ternak';
    protected $primaryKey = 'id';

    protected $allowedFields = [

    'kabupaten_id',
    'jenis_ternak_id',
    'tahun',
    'berat_hidup',
    'berat_karkas',
    'berat_daging_murni',
    'berat_jeroan',
    'betina_laktasi_terhadap_populasi',
    'produktivitas_susu',
    'produktivitas_telur',
    'konversi_livebird_ke_karkas',

        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}