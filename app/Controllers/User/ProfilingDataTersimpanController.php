<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use CodeIgniter\Database\ConnectionInterface;
use App\Models\PopulasiModel;
use App\Models\ProduksiModel;
use App\Models\HargaModel;

class ProfilingDataTersimpanController extends BaseController
{
    
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    
    public function index()
    {
        $popModel = new PopulasiModel();
        $prodModel = new ProduksiModel();
        $hargaModel = new HargaModel();

        // 🎯 ambil filter global
        $tahun = $this->request->getGet('tahun') ?? null;

        /*🔵 POPULASI*/
        $data['populasi'] = $popModel->getData(14, $tahun, null, 'populasi');
        $data['pager_populasi'] = $popModel->pager;
        $data['total_populasi'] = $popModel->countAll();

        /*🟢 PRODUKSI*/
        $data['produksi'] = $prodModel->getData(21, $tahun, null, 'produksi');
        $data['pager_produksi'] = $prodModel->pager;
        $data['total_produksi'] = $prodModel->countAll();

        /*🟡 HARGA*/
        $data['harga'] = $hargaModel->getData(10, $tahun, null, null, 'harga');
        $data['pager_harga'] = $hargaModel->pager;
        $data['total_harga'] = $hargaModel->countAll();

        /*🎯 FILTER DATA*/
        $data['list_tahun'] = $popModel->getTahunList(); // cukup ambil dari 1 model
        $data['tahun'] = $tahun;

        return view('user/profilingdatatersimpan', $data);
    }
}