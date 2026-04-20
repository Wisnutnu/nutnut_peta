<?php

namespace App\Models;

use CodeIgniter\Model;

class UploadStagingModel extends Model
{
    protected $table      = 'upload_staging';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'kategori',
        'data_json',
        'jumlah_row',
        'status',
        'catatan_admin',
        'created_at',
        'file_name',
        'file_path',
        'source'
    ];

    protected $useTimestamps = false;
}
// milik user upload data populasi, produksi, harga, dll yang masuk ke staging dulu sebelum di approve admin