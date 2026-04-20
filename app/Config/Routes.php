<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//buat login
$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login'); // misal ini dihapus malah eroro routes 404
$routes->post('/loginProcess', 'Auth::loginProcess');
$routes->get('/logout', 'Auth::logout');

// routes master
$routes->group('master', ['filter' => 'auth:master'], function($routes) {

    $routes->get('/', 'Master\DashboardController::index');
    $routes->get('statistik', 'Master\DashboardController::statistik');
    $routes->get('log', 'Master\DashboardController::log');
    $routes->get('users', 'Master\DashboardController::users');
    $routes->get('roles','Master\DashboardController::roles');
    $routes->get('security', 'Master\DashboardController::security');
    $routes->get('theme', 'Master\DashboardController::theme');
    $routes->get('branding', 'Master\DashboardController::branding');
    $routes->get('layout', 'Master\DashboardController::layout');
    $routes->get('kategori', 'Master\DashboardController::kategori');
    $routes->get('backupdanrestore', 'Master\DashboardController::backupdanrestore');
    $routes->get('/download-template/(:any)', 'Admin\UploadController::downloadTemplate/$1');

});
// routes admin
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {

    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('upload', 'Admin\UploadController::index');
    $routes->post('upload', 'Admin\UploadController::upload');

    $routes->get('carapenggunaan', 'Admin\CarapenggunaanController::index');
    $routes->get('/download-data', 'Admin\UploadController::downloadData');       // Populasi
    $routes->get('/download-produksi', 'Admin\UploadController::downloadProduksi'); // Produksi
    $routes->get('/download-harga', 'Admin\UploadController::downloadHarga');       // Harga
    $routes->get('download-template/(:any)', 'Admin\UploadController::downloadTemplate/$1');
    
    // data approved
    $routes->get('data_approved', 'Admin\Profiling\ApprovalProfilingController::index'); //milik profiling
    $routes->get('infrastruktur/approved', 'Admin\Infrastruktur\ApprovalInfrastrukturController::index'); //milik infrastruktur

    // profiling
    $routes->get('profiling', 'Admin\ProfilingController::index'); //peta
    $routes->get('profiling/staging', 'Admin\Profiling\StagingController::index');
    $routes->get('profiling/staging/preview/(:num)', 'Admin\Profiling\StagingController::preview/$1');
    $routes->post('profiling/staging/approve/(:num)', 'Admin\Profiling\StagingController::approve/$1');
    $routes->post('profiling/staging/reject/(:num)', 'Admin\Profiling\StagingController::reject/$1');
    $routes->get('/staging/download/(:num)', 'Admin\StagingController::download/$1'); // download file asli dari staging

    // data pokok staging
    $routes->get('datapokok', 'Admin\DataPokokController::index');
    $routes->get('datapokok/approve/(:num)', 'Admin\DataPokokController::approve/$1');
    $routes->get('datapokok/reject/(:num)', 'Admin\DataPokokController::reject/$1');

    // infrastruktur &staging
    $routes->get('infrastruktur', 'Admin\Infrastruktur\InfrastrukturController::index');
    $routes->get('infrastruktur/staging', 'Admin\Infrastruktur\InfrastrukturController::index');
    $routes->post('staging/infrastruktur/approve/(:num)', 'Admin\Infrastruktur\StagingController::approve/$1');
    $routes->post('staging/infrastruktur/reject/(:num)', 'Admin\Infrastruktur\StagingController::reject/$1');

    //penyuluh
    $routes->get('staging/penyuluh', 'Admin\Penyuluh\ApprovalPemotongan::staging');
    $routes->get('approval/penyuluh', 'Admin\Penyuluh\ApprovalPemotongan::final');
    $routes->get('approval/penyuluh/approve/(:num)', 'Admin\Penyuluh\ApprovalPemotongan::approve/$1');
    $routes->get('approval/penyuluh/reject/(:num)', 'Admin\Penyuluh\ApprovalPemotongan::reject/$1');
    $routes->get('rekap/penyuluh', 'Admin\Penyuluh\ApprovalPemotongan::rekapBulanan');
    $routes->get('approval/penyuluh/final', 'Admin\Penyuluh\ApprovalPemotongan::final');
    $routes->get('export/excel', 'Admin\Penyuluh\ApprovalPemotongan::exportExcel');
    //utk populaisi penyuluh
    $routes->get('populasi', 'Admin\PenyuluhPopulasiController::index');

    // data tabular di peta
    $routes->get('profiling/getTableData', 'Admin\ProfilingController::getTableData');

    //kab mana saja yg sudah mengisi data
    $routes->get('profiling/getKabupatenTerisi', 'Admin\ProfilingController::getKabupatenTerisi');

    // API data provinsi
    $routes->get('api/data-provinsi/(:segment)', 'Admin\ApiController::getByProvinsi/$1');
    $routes->get('data_approved', 'Admin\DataApprovedController::index');

    // user management admin utk nambah user baru
        $routes->get('managementuser', 'Admin\UserController::index');
        $routes->get('managementuser/create', 'Admin\UserController::create');
        $routes->post('managementuser/store', 'Admin\UserController::store');
        $routes->get('managementuser/edit/(:num)', 'Admin\UserController::edit/$1');
        $routes->post('managementuser/update/(:num)', 'Admin\UserController::update/$1');
        $routes->get('managementuser/delete/(:num)', 'Admin\UserController::delete/$1');
        
        $routes->get('wilayah/kabupaten/(:num)', 'Admin\WilayahController::kabupaten/$1');
        $routes->get('wilayah/kecamatan/(:num)', 'Admin\WilayahController::kecamatan/$1');
        $routes->get('managementuser/edit/(:num)', 'Admin\UserController::edit/$1');
        $routes->post('managementuser/update/(:num)', 'Admin\UserController::update/$1');
});

// Routes User
$routes->group('user', ['filter' => 'auth:user'], function($routes) {

    $routes->get('dashboard', 'User\DashboardController::index');
    $routes->get('upload', 'User\UploadController::index');
    $routes->post('upload/produksi', 'User\UploadController::storeProduksi');
    $routes->post('upload/harga', 'User\UploadController::storeHarga');
    $routes->get('profiling', 'User\ProfilingController::index');
    
    //datapokok
    $routes->get('datapokok', 'User\DataPokokController::index');
    $routes->get('datapokok/create', 'User\DataPokokController::create');
    $routes->post('datapokok/store', 'User\DataPokokController::store');

    //data tersimpan
    $routes->get('data-profilingdatatersimpan', 'User\ProfilingDataTersimpanController::index');
    $routes->get('data-infrastrukturtersimpan', 'User\InfrastrukturDataTersimpanController::index');
    
    // parameter khusus user
    $routes->post('parameter/store', 'User\ParameterController::store');

    //infrastruktur
    $routes->get('infrastruktur', 'User\UploadInfrastrukturController::Infrastruktur');
    $routes->post('upload/saveInfrastruktur', 'User\UploadController::saveInfrastruktur');
    $routes->get('upload/editInfrastruktur/(:num)', 'User\UploadController::editInfrastruktur/$1');
    $routes->post('upload/updateInfrastruktur/(:num)', 'User\UploadController::updateInfrastruktur/$1');
    $routes->get('upload/deleteInfrastruktur/(:num)', 'User\UploadController::deleteInfrastruktur/$1');

    //prfiling
    $routes->post('upload/populasi', 'User\UploadController::storePopulasi');
    $routes->post('upload/produksi', 'User\UploadController::storeProduksi');
    $routes->post('upload/harga', 'User\UploadController::storeHarga');

    // PENYULUH
    $routes->group('penyuluh', function($routes){
        $routes->get('/', 'Penyuluh\InputPemotongan::index');
        // $routes->get('create', 'Penyuluh\InputPemotongan::create');
        // $routes->post('store', 'Penyuluh\InputPemotongan::store');
        });

        $routes->group('populasi', function($routes){
            $routes->get('/', 'User\Penyuluh\InputPopulasiController::index');
            $routes->get('create', 'User\Penyuluh\InputPopulasiController::create');
            $routes->post('store', 'User\Penyuluh\InputPopulasiController::store');

            $routes->get('edit/(:num)', 'User\Penyuluh\InputPopulasiController::edit/$1');
            $routes->post('update/(:num)', 'User\Penyuluh\InputPopulasiController::update/$1');
            $routes->get('delete/(:num)', 'User\Penyuluh\InputPopulasiController::delete/$1');
            $routes->get('kirim/(:num)', 'User\Penyuluh\InputPopulasiController::kirim/$1');

        });

        // validasi populasi kabupaten
        $routes->group('kabupaten', function($routes) {
            $routes->get('populasi', 'User\Kabupaten\ValidasiPopulasiController::index');
            $routes->get('populasi/approve/(:num)', 'User\Kabupaten\ValidasiPopulasiController::approve/$1');
            $routes->get('populasi/reject/(:num)', 'User\Kabupaten\ValidasiPopulasiController::reject/$1');
        });
    //coba 
    $routes->get('data-tersimpan', 'User\DataTersimpanController::index');
    
    // Profile
    $routes->get('profile', 'User\ProfileController::index');
    $routes->post('profile/update', 'User\ProfileController::update');
        //ganti pasword
        $routes->get('ganti-password', 'User\ProfileController::formPassword');
        $routes->post('ganti-password', 'User\ProfileController::updatePassword');

    //rekap di menu provinsi
    $routes->get('provinsi/populasi', 'User\Provinsi\TabularPopulasiController::index');
});

// Routes Public untuk peta
    $routes->get('/', 'Peta::index'); //untuk peta_view
    $routes->get('/profiling', 'ProfilingController::index'); // untuk halaman profiling

    $routes->get('/infrastruktur', 'InfrastrukturController::index');


    $routes->get('/download-template/(:any)', 'UploadController::downloadTemplate/$1');

// // Routes profiling
$routes->get('/profiling', 'ProfilingController::index');
$routes->get('/profiling/data', 'ProfilingController::getDataProfiling'); //ini tdk dipake
$routes->get('/profiling/getData', 'ProfilingController::getData');
$routes->get('/profiling/getAnalysis', 'ProfilingController::getAnalysis');
$routes->get('/profiling/getGrowth', 'ProfilingController::getGrowth'); //ini blom dipake
$routes->get('/carapenggunaan', 'CaraPenggunaanController::index');

// routes untuk data pokok
$routes->group('datapokok', ['filter' => 'auth:admin'], function($routes) {

    $routes->get('/', 'DataPokok\ProduksiController::index');

    $routes->get('create', 'DataPokok\ProduksiController::create');
    $routes->post('store', 'DataPokok\ProduksiController::store');

    $routes->get('parameter', 'DataPokok\ParameterController::index');
    $routes->get('master', 'DataPokok\MasterController::index');
    $routes->get('hasil', 'DataPokok\HasilController::index');

    // $routes->get('edit/(:num)', 'DataPokok\ProduksiController::edit/$1');
    // $routes->post('update/(:num)', 'DataPokok\ProduksiController::update/$1');
    // $routes->get('delete/(:num)', 'DataPokok\ProduksiController::delete/$1');

});


// Routes upload Excel
// $routes->get('/upload', 'UploadController::index');  // untuk menampilkan halaman upload
// $routes->post('/upload', 'UploadController::upload'); // untuk proses upload file
// $routes->get('/download-data', 'UploadController::downloadData');       // Populasi
// $routes->get('/download-produksi', 'UploadController::downloadProduksi'); // Produksi
// $routes->get('/download-harga', 'UploadController::downloadHarga');       // Harga
// $routes->post('/upload', 'UploadController::upload');

//routes untuk staging upload /tunggu approve admin
// routes staging upload (admin) sudah dicopy ke admin/




// Routes API Data Provinsi
$routes->get('/api/data-provinsi/(:segment)', 'ApiData::getByProvinsi/$1');
$routes->get('ApiData/profiling', 'ApiData::profiling');

// // API Routes peta
// $routes->group('api', function($routes) {
//     $routes->get('sppg', 'ApiController::dataSppg'); // Endpoint: /api/sppg
//     $routes->get('uph', 'ApiController::dataUph');   // Endpoint: /api/uph
//     $routes->get('puskeswan', 'ApiController::datapuskeswan');// Endpoint: /api/puskeswan
//     $routes->get('koperasiPKH', 'ApiController::datakoperasiPKH'); // Endpoint: /api/koperasiPKH
//     $routes->get('labbibit', 'ApiController::datalabbibit'); // Endpoint: /api/labbibit
//     $routes->get('labkesmavet', 'ApiController::datalabkesmavet'); // Endpoint: /api/labkesmavet
//     $routes->get('labpakan', 'ApiController::datalabpakan'); // Endpoint: /api/labpakan
//     $routes->get('pasarternak', 'ApiController::datapasarternak'); // Endpoint: /api/pasarternak
//     $routes->get('rph', 'ApiController::datarph'); // Endpoint: /api/rph
//     $routes->get('klinikhewan', 'ApiController::dataklinikhewan'); // Endpoint: /api/Klinikhewan
//     $routes->get('labkeswan', 'ApiController::datalabkeswan'); // Endpoint: /api/labkeswan
//     $routes->get('upt', 'ApiController::dataupt'); // Endpoint: /api/upt
//     $routes->get('cari-lokasi', 'ApiController::cariLokasi'); // Endpoint untuk search lokasi
   

//  });
