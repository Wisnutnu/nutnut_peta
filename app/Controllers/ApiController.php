<?php namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\SppgModel;
use App\Models\UphModel;
use App\Models\PuskeswanModel; 
use App\Models\KlinikhewanModel; 
use App\Models\KoperasiPKHModel; 
use App\Models\LabbibitModel;
use App\Models\LabkesmavetModel;
use App\Models\LabkeswanModel;
use App\Models\LabpakanModel;
use App\Models\PasarternakModel;
use App\Models\RphModel;
use App\Models\UptModel;

class ApiController extends BaseController
{
    use ResponseTrait;

    public function dataSppg()
    {
        $model = new SppgModel();
        return $this->respond($model->getAllTitik());
    }
    
    public function dataUph()
    {
        $model = new UphModel();
        return $this->respond($model->getAllTitik());
    }

    public function datapuskeswan()
    {
        $model = new PuskeswanModel();
        return $this->respond($model->getAllTitik());
    }

    public function dataklinikhewan()
    {
        $model = new KlinikhewanModel();
        return $this->respond($model->getAllTitik());
    }

    public function datakoperasiPKH()
    {
        $model = new KoperasiPKHModel();
        return $this->respond($model->getAllTitik());
    }

    public function datalabbibit()
    {
        $model = new LabbibitModel();
        return $this->respond($model->getAllTitik());
    }

    public function datalabkesmavet()
    {
        $model = new LabkesmavetModel();
        return $this->respond($model->getAllTitik());
    }

    public function datalabpakan()
    {
        $model = new LabpakanModel();
        return $this->respond($model->getAllTitik());
    }

    public function datapasarternak()
    {
        $model = new PasarternakModel();
        return $this->respond($model->getAllTitik());
    }

    public function datarph()
    {
        $model = new RphModel();
        return $this->respond($model->getAllTitik());
    }

    public function datalabkeswan()
    {
        $model = new LabkeswanModel();
        return $this->respond($model->getAllTitik());
    }

    public function dataupt()
    {
        $model = new UptModel();
        return $this->respond($model->getAllTitik());
    }
}
