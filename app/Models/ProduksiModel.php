<?php namespace App\Models;

use CodeIgniter\Model;

class ProduksiModel extends Model
{
    protected $table = 'produksi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'provinsi_id',
        'kabupaten_id',
        'jenis_produksi',
        'tahun',
        'jumlah'
    ];

    public function getTahunList()
{
    return $this->select('tahun')
        ->distinct()
        ->orderBy('tahun', 'DESC')
        ->findAll();
}

public function getJenisList()
{
    return $this->select('jenis_produksi')
        ->distinct()
        ->orderBy('jenis_produksi', 'ASC')
        ->findAll();
}

    public function getData($limit = 10, $tahun = null, $jenis = null, $group = 'produksi')
{
    $builder = $this->table($this->table)
        ->select('produksi.*, p.nama_provinsi, k.nama_kabupaten')
        ->join('master_provinsi p', 'produksi.provinsi_id = p.id')
        ->join('master_kabupaten k', 'produksi.kabupaten_id = k.id');

    // 🔐 filter wilayah
    $session = session();
    if ($session->get('role') == 'user') {
        $builder->where('produksi.kabupaten_id', $session->get('kabupaten_id'));
    } elseif ($session->get('role') == 'admin') {
        $builder->where('produksi.provinsi_id', $session->get('provinsi_id'));
    }

    // 🎯 filter tahun
    if (!empty($tahun)) {
        $builder->where('produksi.tahun', $tahun);
    }

    // 🎯 filter jenis produksi
    if (!empty($jenis)) {
        $builder->where('produksi.jenis_produksi', $jenis);
    }

    return $builder->paginate($limit, $group);
}
}