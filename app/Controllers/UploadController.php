<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\UploadStagingModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadController extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // Halaman upload + preview data Populasi, Produksi, Harga
    public function index()
    {
        $data['populasi']  = $this->db->table('populasi')->get()->getResultArray();
        $data['produksi']  = $this->db->table('produksi')->get()->getResultArray();
        $data['harga']     = $this->db->table('harga')->get()->getResultArray();

        return view('upload_view', $data);
    }

//staging upload/tunggu review admin
public function upload()
{
    $kategori = $this->request->getPost('kategori');
    $file     = $this->request->getFile('excel_file');

    if (!$kategori) {
        return redirect()->back()->with('error', 'Kategori belum dipilih');
    }

    if (!$file || !$file->isValid()) {
        return redirect()->back()->with('error', 'File tidak valid');
    }

    // Baca Excel langsung dari temp file (tidak perlu simpan fisik dulu)
    $spreadsheet = IOFactory::load($file->getTempName());
    $sheet = $spreadsheet->getActiveSheet()->toArray();

    // Hapus header
    unset($sheet[0]);

    if (count($sheet) === 0) {
        return redirect()->back()->with('error', 'File kosong atau hanya header');
    }

    $staging = new UploadStagingModel();

// Simpan file fisik
$newName = $file->getRandomName();
$file->move(WRITEPATH . 'uploads', $newName);

$staging->insert([
    'user_id'     => 1,
    'kategori'    => $kategori,
    'file_name'   => $file->getClientName(), // nama asli user
    'file_path'   => $newName,               // nama file di server
    'data_json'   => json_encode(array_values($sheet)),
    'jumlah_row'  => count($sheet),
    'status'      => 'pending'
]);


    return redirect()->back()->with(
        'success',
        'Upload berhasil. Data masuk staging dan menunggu approval admin.'
    );
}
//dowenload template
public function downloadTemplate($kategori)
{
    $filePath = FCPATH . "templates/template_$kategori.xlsx";

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'Template tidak ditemukan');
    }

    return $this->response->download($filePath, null);
}


    // Download data Populasi sebagai Excel
    public function downloadData()
    {
        $populasi = $this->db->table('populasi')->get()->getResultArray();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'Provinsi')
              ->setCellValue('B1', 'Kab/Kota')
              ->setCellValue('C1', 'Jenis Ternak')
              ->setCellValue('D1', 'Tahun')
              ->setCellValue('E1', 'Jumlah Populasi');

        // Isi data
        $rowNumber = 2;
        foreach($populasi as $row) {
            $sheet->setCellValue('A'.$rowNumber, $row['provinsi'])
                  ->setCellValue('B'.$rowNumber, $row['kab_kota'])
                  ->setCellValue('C'.$rowNumber, $row['jenis_ternak'])
                  ->setCellValue('D'.$rowNumber, $row['tahun'])
                  ->setCellValue('E'.$rowNumber, $row['jumlah_populasi']);
            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'data_populasi.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

// download produksi
    public function downloadProduksi()
    {
        $produksi = $this->db->table('produksi')->get()->getResultArray();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'Provinsi')
            ->setCellValue('B1', 'Kab/Kota')
            ->setCellValue('C1', 'Jenis Produksi')
            ->setCellValue('D1', 'Tahun')
            ->setCellValue('E1', 'Jumlah Produksi');

        // Isi data
        $rowNumber = 2;
        foreach($produksi as $row) {
            $sheet->setCellValue('A'.$rowNumber, $row['provinsi'])
                ->setCellValue('B'.$rowNumber, $row['kab_kota'])
                ->setCellValue('C'.$rowNumber, $row['jenis_produksi'])
                ->setCellValue('D'.$rowNumber, $row['tahun'])
                ->setCellValue('E'.$rowNumber, $row['jumlah']);
            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'data_produksi.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
    // dowenload Harga
    public function downloadHarga()
{
    $harga = $this->db->table('harga')->get()->getResultArray();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header kolom
    $sheet->setCellValue('A1', 'Provinsi')
          ->setCellValue('B1', 'Kab/Kota')
          ->setCellValue('C1', 'Jenis Ternak')
          ->setCellValue('D1', 'Kategori')
          ->setCellValue('E1', 'Tahun')
          ->setCellValue('F1', 'Harga');

    // Isi data
    $rowNumber = 2;
    foreach($harga as $row) {
        $sheet->setCellValue('A'.$rowNumber, $row['provinsi'])
              ->setCellValue('B'.$rowNumber, $row['kab_kota'])
              ->setCellValue('C'.$rowNumber, $row['jenis_ternak'])
              ->setCellValue('D'.$rowNumber, $row['kategori'])
              ->setCellValue('E'.$rowNumber, $row['tahun'])
              ->setCellValue('F'.$rowNumber, $row['harga']);
        $rowNumber++;
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'data_harga.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'. $filename .'"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}


}
