<?php

namespace App\Controllers\Master;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        return view('master/dashboard');
    }

    public function statistik()
    {
        return view('master/statistik');
    }

    public function log()
    {
        return view('master/log');
    }

    public function users()
    {
        return view('master/users');
    }

    public function roles()
    {
        return view('master/roles');
    }

    public function security()
    {
        return view('master/security');
    }

    public function theme()
    {
        return view('master/theme');
    }
    public function branding()
    {
        return view('master/branding');
    }
    public function layout()
    {
        return view('master/layout');
    }
    public function kategori()
    {
        return view('master/kategori');
    }
    public function backupdanrestore()
    {
        return view('master/backupdanrestore');
    }
    
}
