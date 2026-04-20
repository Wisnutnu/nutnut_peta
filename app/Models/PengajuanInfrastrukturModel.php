<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanInfrastrukturModel extends Model
{
    protected $table = 'pengajuan_infrastruktur';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'jenis_infrastruktur',
        'nama_tempat',
        'alamat',
        'latitude',
        'longitude',
        'keterangan',
        'status',
        'created_at',
        'verified_at',
        'verified_by'
    ];

    protected $useTimestamps = false;
}