<?php

namespace App\Controllers\Admin\Penyuluh;

use App\Controllers\BaseController;
use App\Models\PenyuluhPemotonganHarianModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ApprovalPemotongan extends BaseController
{
    public function staging()
    {
        $model = new PenyuluhPemotonganHarianModel();

        $data['pemotongan'] = $model
            ->where('status', 'pending')
            ->findAll();

        return view('admin/staging_pemotongan_list', $data);
    }

    public function approve($id)
    {
        $model = new PenyuluhPemotonganHarianModel();

        $model->update($id, [
            'status'      => 'approve',
            'approved_by' => session()->get('user_id'), // atau admin_id
            'approved_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/admin/staging/penyuluh');
    }

    public function final()
{
    $bulan = $this->request->getGet('bulan');
    $model = new PenyuluhPemotonganHarianModel();

    // =========================
    // DATA LIST APPROVED
    // =========================
    $listQuery = $model->where('status', 'approve');

    if ($bulan) {
        $listQuery->where('bulan', $bulan);
    }

    $data['pemotongan'] = $listQuery->orderBy('tanggal', 'DESC')->findAll();


    // =========================
    // DATA REKAP
    // =========================
    $rekapQuery = $model
    ->select("
        SUM(sapi_potong) as total_sapi_potong,
        SUM(sapi_perah) as total_sapi_perah,
        SUM(kerbau) as total_kerbau
    ")
    ->where('status', 'approve');

if ($bulan) {
    $rekapQuery->where('bulan', $bulan);
}

$rekap = $rekapQuery->first();

// Kalau NULL, isi dengan 0
if (!$rekap) {
    $rekap = [
        'total_sapi_potong' => 0,
        'total_sapi_perah'  => 0,
        'total_kerbau'      => 0,
    ];
}

$data['rekap'] = $rekap;
$data['bulan'] = $bulan;

    return view('admin/approval_final_pemotongan', $data);
}

public function exportExcel()
{
    $bulan = $this->request->getGet('bulan');
    $model = new PenyuluhPemotonganHarianModel();

    $query = $model->where('status', 'approve');

    if ($bulan) {
        $query->where('bulan', $bulan);
    }

    $data = $query->findAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'Tanggal');
    $sheet->setCellValue('B1', 'Tempat');
    $sheet->setCellValue('C1', 'Sapi Potong');
    $sheet->setCellValue('D1', 'Sapi Perah');
    $sheet->setCellValue('E1', 'Kerbau');

    $row = 2;
    foreach ($data as $d) {
        $sheet->setCellValue('A'.$row, $d['tanggal']);
        $sheet->setCellValue('B'.$row, $d['nama_tempat']);
        $sheet->setCellValue('C'.$row, $d['sapi_potong']);
        $sheet->setCellValue('D'.$row, $d['sapi_perah']);
        $sheet->setCellValue('E'.$row, $d['kerbau']);
        $row++;
    }

    $writer = new Xlsx($spreadsheet);

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="laporan_pemotongan.xlsx"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}

}