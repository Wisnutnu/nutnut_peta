<?php

namespace App\Models;

use CodeIgniter\Model;

class PenyuluhPemotonganHarianModel extends Model
{
    protected $table = 'penyuluh_pemotongan_harian';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'nama_tempat',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'bulan',
        'tanggal',
        'nama_petugas',
        'sapi_potong',
        'sapi_perah',
        'kerbau',
        'status',
        'approved_by',
        'approved_at'
    ];

    protected $useTimestamps = true;
}