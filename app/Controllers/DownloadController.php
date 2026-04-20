<?php

namespace App\Controllers;

class InfrastrukturController extends BaseController
{
public function downloadTemplate($kategori)
{
    $filePath = FCPATH . "templates/template_$kategori.xlsx";

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'Template tidak ditemukan');
    }

    return $this->response->download($filePath, null);
}

}