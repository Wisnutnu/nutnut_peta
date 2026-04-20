<?php

namespace App\Models\ManagementUser;

use CodeIgniter\Model;

class KabupatenModel extends Model
{
    protected $table = 'master_kabupaten';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'provinsi_id',
        'nama_kabupaten',
        'kode_bps',
        'kode_kemendagri',
        'created_at',
        'updated_at'
    ];
}