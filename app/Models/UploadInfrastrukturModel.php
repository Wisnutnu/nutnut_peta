<?php

namespace App\Models;

use CodeIgniter\Model;

class UploadInfrastrukturModel extends Model
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
    'provinsi',     // ✅ wajib
    'kab_kota',     // ✅ wajib
    'status',
    'created_at'
];
    protected $useTimestamps = false;
}
// miliknya user upload infrastruktur, nanti akan masuk ke staging infrastruktur, lalu di approve baru masuk ke tabel infrastruktur