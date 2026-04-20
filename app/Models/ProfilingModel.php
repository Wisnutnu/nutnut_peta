<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilingModel extends Model
{
    protected $table = 'nama_tabel_kamu';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kolom1', 'kolom2', 'kolom3'];
}
