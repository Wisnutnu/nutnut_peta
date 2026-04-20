<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profiling PKH</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

<style>
body {
    margin: 0;
    padding: 0;
    background: #f8f9fa;
    overflow: hidden;
}

/* Sidebar kiri */
.sidebar {
    --sidebar-bg: #058146;
    width: 240px;
    min-height: 100vh;
    background-color: var(--sidebar-bg);
    color: #fff;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1030;
    padding: 15px;
    box-shadow: 5px 0 10px rgba(0, 0, 0, 0.1);
}
.sidebar .nav-link { color: #cdd6ff; }
.sidebar .nav-link:hover,
.sidebar .active-menu { color: #fff; }

/* Navbar */
.navbar {
    position: fixed;
    top: 0;
    left: 240px;
    right: 0;
    z-index: 1000;
    
    background: #053b21ff;     /* warna navbar */
    color: #fff;             /* warna teks */
    height: 56px;            /* tinggi fix */
    display: flex;
    align-items: center;     /* biar teks/logo center vertical */
    padding: 0 20px;
}


/* MAIN LAYOUT */
.main-container {
    display: flex;
    position: absolute;
    top: 56px;
    left: 240px;
    right: 0;
    bottom: 0;
    overflow-y: auto;    /* ⬅ scroll bebas */
    overflow-x: hidden;
    align-items: flex-start; /* penting */
}


</style>

</head>

<body>

<!-- SIDEBAR kiri-->
<div class="sidebar d-flex flex-column">
<img src="<?= base_url('images/logo.png') ?>" class="img-fluid" style="max-height:150px; max-width:150px; display:block; margin:0 auto;">
    
<h4 class="fw-bold text-white mb-4">Latsar</h4>

<div class="sidebar d-flex flex-column">
<img src="<?= base_url('images/logo.png') ?>" class="img-fluid" 
style="max-height:150px; max-width:150px; display:block; margin:0 auto;">
 
    <h4 class="fw-bold text-white mb-4">Latsar</h4>
<ul class="nav flex-column flex-grow-1">
    <li class="nav-item">
        <a class="nav-link" href="/">
            <i class="fas fa-tachometer-alt me-3"></i> Dashboard Utama
        </a>
    </li>
        <li class="nav-item">
        <a class="nav-link" href="infrastruktur">
            <i class="fas fa-tachometer-alt me-3"></i> Infrastruktur PKH
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active-menu" href="/upload">
            <i class="fas fa-upload me-3"></i> Upload Data
        </a>
    </li>
        <li class="nav-item">
        <a class="nav-link" href="/profiling">
            <i class="fas fa-address-card me-3"></i> Profiling
        </a>
    </li>
        <li class="nav-item">
        <a class="nav-link" href="/admin/staging">
            <i class="fas fa-check-circle me-3"></i> Approval Data
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="#">
            <i class="fas fa-project-diagram me-3"></i> Cara penggunaan
        </a>
    </li>
</ul>

</div>
</div>


<!-- NAVBAR- atas -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">🌍 Upload Data </a>

        <div class="ms-auto">
            <button class="btn btn-outline-light me-2"><i class="fas fa-bell"></i></button>
            <button class="btn" 
                    style="background:#058146; color:white; border:1px solid #058146;">
                <i class="fas fa-user-circle me-1"></i> Admin
            </button>

        </div>
        
    </div>
</nav>

<!-- database view -->
<div class="main-container">
    <div class="container-fluid py-4">

    <!-- Card Upload -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-upload me-1"></i> Upload Data Excel</h5>
            </div>
        <div class="card-body">

<!-- Dropdown Kategori -->
<form action="<?= base_url('/upload') ?>" method="post" enctype="multipart/form-data">

    <div class="mb-3">
        <label for="kategori" class="form-label">Kategori Data</label>
        <select class="form-select" name="kategori" id="kategori" required>
            <option value="" selected disabled>Pilih Kategori</option>
            <option value="populasi">Populasi</option>
            <option value="produksi">Produksi</option>
            <option value="harga">Harga</option>
        </select>
    </div>

    <!-- Input File -->
    <div class="mb-3">
        <input type="file" 
               class="form-control" 
               name="excel_file" 
               accept=".xls,.xlsx" 
               required>
    </div>

    <!-- Tombol Upload + Download -->
    <div class="d-flex gap-2 align-items-center">

        <!-- Upload -->
        <button class="btn btn-success" type="submit">
            <i class="fas fa-upload me-1"></i> Upload
        </button>

        <!-- Download Template -->
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" 
                    type="button" 
                    data-bs-toggle="dropdown">
                <i class="fas fa-download me-1"></i> Download Template
            </button>

            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="<?= base_url('/download-template/populasi') ?>">
                        📗1. Template Populasi
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?= base_url('/download-template/produksi') ?>">
                        📙2. Template Produksi
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?= base_url('/download-template/harga') ?>">
                        📕3. Template Harga
                    </a>
                </li>
            </ul>
        </div>

    </div>
</form>

    <!-- Feedback -->
                <?php if(session()->getFlashdata('success')): ?>
                    <div class="alert alert-success mt-2"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if(session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger mt-2"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

            </div>
        </div>


<!-- ======================================= -->
    <!-- tombol dowenload populasi-->
    <a href="<?= base_url('/download-data') ?>" class="btn btn-success me-2">
        <i class="fas fa-download me-1"></i> Download Populasi
    </a>

    <!-- tabel populasi-->
        <div class="card shadow-sm">
            <div class="card-header text-white" style="background-color: #058146;">
            <h5 class="mb-0"><i class="fas fa-table me-1"></i> Data Populasi Saat Ini</h5>
            </div>

            <div class="card-body table-responsive" style="max-height:400px; overflow-y:auto;">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Provinsi</th>
                            <th>Kab/Kota</th>
                            <th>Jenis Ternak</th>
                            <th>Tahun</th>
                            <th>Jumlah Populasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($populasi as $row): ?>
                        <tr>
                            <td><?= $row['provinsi'] ?></td>
                            <td><?= $row['kab_kota'] ?></td>
                            <td><?= $row['jenis_ternak'] ?></td>
                            <td><?= $row['tahun'] ?></td>
                            <td><?= $row['jumlah_populasi'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

  <!-- tombol dowenlod produksi -->
<div class="mt-4">
    <a href="<?= base_url('/download-produksi') ?>" class="btn btn-warning">
        <i class="fas fa-download me-1"></i> Download Produksi
    </a>
</div>

<!-- Card Produksi -->
    <div class="card shadow-sm mt-4">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0"><i class="fas fa-industry me-1"></i> Data Produksi Saat Ini</h5>
    </div>
    <div class="card-body table-responsive" style="max-height:400px; overflow-y:auto;">
        <table class="table table-bordered table-striped mb-0">
            <thead>
                <tr>
                    <th>Provinsi</th>
                    <th>Kab/Kota</th>
                    <th>Jenis Produksi</th>
                    <th>Tahun</th>
                    <th>Jumlah Produksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($produksi as $row): ?>
                <tr>
                    <td><?= $row['provinsi'] ?></td>
                    <td><?= $row['kab_kota'] ?></td>
                    <td><?= $row['jenis_produksi'] ?></td>
                    <td><?= $row['tahun'] ?></td>
                    <td><?= $row['jumlah'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- tombol dowenlod harga -->
 <div class="mt-4">
    <a href="<?= base_url('/download-harga') ?>" class="btn btn-danger mt-4">
        <i class="fas fa-download me-1"></i> Download Data Harga
        </a>
</div>

<!-- Card Harga -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0"><i class="fas fa-dollar-sign me-1"></i> Data Harga Saat Ini</h5>
    </div>
    <div class="card-body table-responsive" style="max-height:400px; overflow-y:auto;">
        <table class="table table-bordered table-striped mb-0">
            <thead>
                <tr>
                    <th>Provinsi</th>
                    <th>Kab/Kota</th>
                    <th>Jenis Ternak</th>
                    <th>Kategori</th>
                    <th>Tahun</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($harga as $row): ?>
                <tr>
                    <td><?= $row['provinsi'] ?></td>
                    <td><?= $row['kab_kota'] ?></td>
                    <td><?= $row['jenis_ternak'] ?></td>
                    <td><?= $row['kategori'] ?></td>
                    <td><?= $row['tahun'] ?></td>
                    <td><?= $row['harga'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</div>
</div>

</body>
</html>