<?php namespace App\Models;

use CodeIgniter\Model;

class PopulasiModel extends Model
{
    protected $table = 'populasi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'provinsi_id',
        'kabupaten_id',
        'jenis_ternak',
        'tahun',
        'jumlah_populasi'
    ];

    // 🔥 ambil data + filter + pagination
    public function getData($limit = 10, $tahun = null, $jenis = null, $group = 'default')
{
    $builder = $this->table($this->table)
        ->select('populasi.*, p.nama_provinsi, k.nama_kabupaten')
        ->join('master_provinsi p', 'populasi.provinsi_id = p.id')
        ->join('master_kabupaten k', 'populasi.kabupaten_id = k.id');

    // 🔐 filter wilayah user
    $session = session();

    if ($session->get('role') == 'user') {
        $builder->where('populasi.kabupaten_id', $session->get('kabupaten_id'));
    } elseif ($session->get('role') == 'admin') {
        $builder->where('populasi.provinsi_id', $session->get('provinsi_id'));
    }

    // 🎯 filter tahun
    if (!empty($tahun)) {
        $builder->where('populasi.tahun', $tahun);
    }

    // 🎯 filter jenis
    if (!empty($jenis)) {
        $builder->where('populasi.jenis_ternak', $jenis);
    }

    return $builder->paginate($limit, $group);
}

    // 🔥 ambil list tahun (buat dropdown)
    public function getTahunList()
    {
        return $this->select('tahun')
            ->distinct()
            ->orderBy('tahun', 'DESC')
            ->findAll();
    }
}