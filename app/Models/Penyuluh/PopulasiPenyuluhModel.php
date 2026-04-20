<?php

namespace App\Models\Penyuluh;

use CodeIgniter\Model;

class PopulasiPenyuluhModel extends Model
{
    protected $table = 'populasi_penyuluh';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id_user',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'nama_pemilik_ternak',
        'no_telp',
        'jenis_ternak_id',
        'latitude',
        'longitude',
        'jumlah',
        'tahun',
        'status',
        'keterangan'
    ];

    protected $useTimestamps = true;
}