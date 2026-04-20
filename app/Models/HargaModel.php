<?php namespace App\Models;

use CodeIgniter\Model;

class HargaModel extends Model
{
    protected $table = 'harga';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'provinsi_id',
        'kabupaten_id',
        'jenis_ternak',
        'kategori',
        'tahun',
        'harga'
    ];

    public function getData($limit = 10, $tahun = null, $jenis = null, $kategori = null, $group = 'harga')
{
    $builder = $this->table($this->table)
        ->select('harga.*, p.nama_provinsi, k.nama_kabupaten')
        ->join('master_provinsi p', 'harga.provinsi_id = p.id')
        ->join('master_kabupaten k', 'harga.kabupaten_id = k.id');

    // 🔐 filter wilayah
    $session = session();
    if ($session->get('role') == 'user') {
        $builder->where('harga.kabupaten_id', $session->get('kabupaten_id'));
    } elseif ($session->get('role') == 'admin') {
        $builder->where('harga.provinsi_id', $session->get('provinsi_id'));
    }

    // 🎯 filter
    if (!empty($tahun)) {
        $builder->where('harga.tahun', $tahun);
    }

    if (!empty($jenis)) {
        $builder->where('harga.jenis_ternak', $jenis);
    }

    if (!empty($kategori)) {
        $builder->where('harga.kategori', $kategori);
    }

    return $builder->paginate($limit, $group);
}
}