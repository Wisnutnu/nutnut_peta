<?php

namespace App\Models\ManagementUser;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama',
        'username',
        'password',
        'role',
        'provinsi_id',
        'kabupaten_id',
        'kecamatan_id',
        'level_user',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $beforeInsert = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }

        return $data;
    }
    protected $useTimestamps = true;
}