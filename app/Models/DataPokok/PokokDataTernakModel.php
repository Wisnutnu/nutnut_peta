<?php namespace App\Models\DataPokok;

use CodeIgniter\Model;

class PokokDataTernakModel extends Model
{
    protected $table = 'pokok_data_ternak';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'provinsi_id',
        'kabupaten_id',
        'jenis_ternak_id',
        'tahun',
        'populasi',
        'pemotongan_rph',
        'pemotongan_luar_rph',
        'pemotongan_tidak_tercatat',
        'produksi_susu',
        'produksi_telur'
    ];
}
