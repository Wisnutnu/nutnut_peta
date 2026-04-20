<?php

namespace App\Models\ManagementUser;

use CodeIgniter\Model;

class ProvinsiModel extends Model
{
    protected $table = 'master_provinsi';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama_provinsi',
        'kode_bps',
        'kode_kemendagri',
        'created_at',
        'updated_at'
    ];
}