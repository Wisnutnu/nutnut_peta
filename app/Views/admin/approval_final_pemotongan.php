<?= $this->include('layout/admin/header') ?>
<?= $this->include('layout/admin/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_admin') ?>

<div class="main-container p-4">
    <div class="content-wrapper">
    <h3 class="mb-4">📊 Data Pemotongan (Final - Approved)</h3>
        <form method="get" class="row align-items-end">
            <div class="col-md-4">

                <input type="month"
                       name="bulan"
                       class="form-control"
                       value="<?= esc($bulan ?? '') ?>">
            </div>

            <div class="col-md-4 mt-3 mt-md-0">
                <button type="submit" class="btn btn-primary">
                    Filter
                </button>
                <a href="<?= base_url('admin/approval/penyuluh/final') ?>"
                   class="btn btn-secondary">
                    Reset
                </a>
            </div>

        </form>
        <hr>
        <!-- expordnya -->
        <a href="<?= base_url('admin/export/excel?bulan=' . ($bulan ?? '')) ?>" 
            class="btn btn-success">
            Export Excel
        </a>

<div class="card mt-3 shadow">
    <div class="card-body">
        
    <!--  -->
    <div class="row text-center mb-4">
    <h4 class="mb-4">📅 Total Bulanan</h4>
        <div class="col-md-4 mb-3">
            <div class="circle-card circle-green">
                <h3><?= esc($rekap['total_sapi_potong'] ?? 0) ?></h3>
                <span>Sapi Potong</span>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="circle-card circle-blue">
                <h3><?= esc($rekap['total_sapi_perah'] ?? 0) ?></h3>
                <span>Sapi Perah</span>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="circle-card circle-orange">
                <h3><?= esc($rekap['total_kerbau'] ?? 0) ?></h3>
                <span>Kerbau</span>
            </div>
        </div>
    </div>

<!--  -->
    <div class="card shadow">
        <div class="card-body">
            <h4 class="mb-4">📅 Rekap Harian</h4>
            <?php if (!empty($pemotongan)) : ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                <th>Nama Penyuluh</th>
                <th>Lokasi</th>
                <th>Sapi Potong</th>
                <th>Sapi Perah</th>
                <th>Kerbau</th>
                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($pemotongan as $row) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= esc($row['nama_petugas']) ?></td>
                                    <td><?= esc($row['nama_tempat']) ?></td>
                                    <td><?= esc($row['sapi_potong']) ?></td>
                                    <td><?= esc($row['sapi_perah']) ?></td>
                                    <td><?= esc($row['kerbau']) ?></td>
                                    
                                    <td>
                                        <span class="badge bg-success">
                                            <?= esc($row['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="alert alert-info">
                    Belum ada data pemotongan yang disetujui.
                </div>
            <?php endif; ?>

        </div>
    </div>
    <hr>
    <!-- bulanan -->
    <div class="card shadow">
        <div class="card-body">
            <h4 class="mb-4">📅 Rekap Bulanan</h4>
            <?php if (!empty($rekap)) : ?>
                <table class="table table-bordered">
                    <tr>
                        <th>Sapi Potong</th>
                        <th>Sapi Perah</th>
                        <th>Kerbau</th>
                        <th>Bulan</th>
                    </tr>
                    <tr>
                        <td><?= esc($rekap['total_sapi_potong'] ?? 0) ?></td>
                        <td><?= esc($rekap['total_sapi_perah'] ?? 0) ?></td>
                        <td><?= esc($rekap['total_kerbau'] ?? 0) ?></td>
                        <td><?= esc($bulan ?? '-') ?></td>
                    </tr>
                </table>
            <?php else : ?>
                <div class="alert alert-info">
                    Tidak ada data approved di bulan ini.
                </div>
            <?php endif; ?>

    
    </div>
    </div>
</div>
</div>
<?= view('layout/admin/footer'); ?>