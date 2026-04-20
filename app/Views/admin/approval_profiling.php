<?= $this->include('layout/admin/header') ?>
<?= $this->include('layout/admin/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_admin') ?>

<div class="main-container p-4">
    <div class="content-wrepper">
        <h4 class="mb-4">📊 Data Final (Approved)</h4>
        
        <!-- TAB UTAMA -->
        <ul class="nav nav-tabs mb-3" id="mainTab">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profiling">
                    📈 Profiling
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#infrastruktur">
                    🏢 Infrastruktur
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#datapokok">
                    🏢 Data Pokok
                </button>
            </li>
        </ul>
        <div class="card mb-3 shadow-sm">
    
            <div class="tab-content">

                <!-- ================= PROFILING ================= -->
                <div class="tab-pane fade show active" id="profiling">

                    <!-- SUB TAB -->
                    <ul class="nav nav-pills mb-3">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#produksi">
                                Produksi
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#populasi">
                                Populasi
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#harga">
                                Harga
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
<!--  -->
                <form method="get" class="row g-2 mb-3">

                    <div class="col-md-3">
                        <input type="text" name="provinsi"
                            value="<?= $_GET['provinsi'] ?? '' ?>"
                            class="form-control"
                            placeholder="Filter Provinsi">
                    </div>

                    <div class="col-md-2">
                        <input type="number" name="tahun"
                            value="<?= $_GET['tahun'] ?? '' ?>"
                            class="form-control"
                            placeholder="Tahun">
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="search"
                            value="<?= $_GET['search'] ?? '' ?>"
                            class="form-control"
                            placeholder="Cari Jenis Data">
                    </div>

                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary">🔍 Filter</button>
                    </div>

                    <div class="col-md-2 d-grid">
                        <a href="<?= base_url('admin/data_approved') ?>"
                        class="btn btn-secondary">Reset</a>
                    </div>

                </form>

                        <!-- PRODUKSI -->
                        <div class="tab-pane fade show active" id="produksi">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Provinsi</th>
                                        <th>Kab/Kota</th>
                                        <th>Jenis Produksi</th>
                                        <th>Tahun</th>
                                        <th>Jumlah</th>
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
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- POPULASI -->
                        <div class="tab-pane fade" id="populasi">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Provinsi</th>
                                        <th>Kab/Kota</th>
                                        <th>Jenis Ternak</th>
                                        <th>Tahun</th>
                                        <th>Jumlah</th>
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
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- HARGA -->
                        <div class="tab-pane fade" id="harga">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Provinsi</th>
                                        <th>Kab/Kota</th>
                                        <th>Jenis</th>
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
                                        <td>Rp <?= number_format($row['harga']) ?></td>
                                    </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <!-- ================= INFRASTRUKTUR ================= -->
                <div class="tab-pane fade" id="infrastruktur">
                    <div class="alert alert-info">
                        Data Infrastruktur akan ditampilkan di sini.
                    </div>
                </div>

                </div>
        </div>
    </div>
</div>

<?= $this->include('layout/admin/footer') ?>