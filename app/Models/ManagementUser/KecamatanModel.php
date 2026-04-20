<?php

namespace App\Models\ManagementUser;

use CodeIgniter\Model;

class KecamatanModel extends Model
{
    protected $table = 'master_kecamatan';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'kabupaten_id',
        'nama_kecamatan',
        'kode_bps',
        'kode_kemendagri',
        'created_at',
        'updated_at'
    ];
}