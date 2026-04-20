<?= $this->include('layout/admin/header') ?>
<?= $this->include('layout/admin/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_admin') ?>

<!-- database view -->
<div class="main-container">
    <div class="container-fluid py-4">
        <!-- TAB ATAS -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <button class="nav-link active">Profiling</button>
            </li>
            <li class="nav-item">
                <button class="nav-link disabled">Infrastruktur (Coming Soon)</button>
            </li>
        </ul>
    <!-- Card Upload -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-upload me-1"></i> Upload Data Excel</h5>
            </div>
        <div class="card-body">

<!-- Dropdown Kategori -->
<form action="<?= base_url('admin/upload') ?>" method="post" enctype="multipart/form-data">

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
                    <a class="dropdown-item" href="<?= base_url('admin/download-template/populasi') ?>">
                        📗1. Template Populasi
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?= base_url('admin/download-template/produksi') ?>">
                        📙2. Template Produksi
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?= base_url('admin/download-template/harga') ?>">
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