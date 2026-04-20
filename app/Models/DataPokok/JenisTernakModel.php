<?php

namespace App\Models\DataPokok;

use CodeIgniter\Model;

class JenisTernakModel extends Model
{
    protected $table = 'pokok_master_jenis_ternak';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama_jenis',
        'kelompok',
        'is_susu',
        'is_telur',
        'created_at',
        'update_at'
    ];

    protected $useTimestamps = true;
}
