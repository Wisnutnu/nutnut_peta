<?= $this->include('layout/user/header') ?>
<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>

<div class="main-container p-4">
    <h4 class="mb-3">Validasi Populasi - Kabupaten</h4>
    <!-- =========================
         FILTER INFO / SUMMARY
    ========================== -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h6>Total Masuk</h6>
                <h4><?= count($masuk); ?></h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h6>Disetujui</h6>
                <h4>
                    <?= count(array_filter($selesai, fn($x) => $x['status'] == 'disetujui')); ?>
                </h4>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h6>Ditolak</h6>
                <h4>
                    <?= count(array_filter($selesai, fn($x) => $x['status'] == 'ditolak')); ?>
                </h4>
            </div>
        </div>
    </div>

    <!-- =========================
         DATA MASUK
    ========================== -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            📥 Data Masuk (Perlu Validasi)
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nama Pemilik</th>
                        <th>Jenis Ternak</th>
                        <th>Jumlah</th>
                        <th>Tahun</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (!empty($masuk)): ?>
                    <?php foreach ($masuk as $row): ?>
                        <tr>
                            <td><?= $row['nama_pemilik_ternak']; ?></td>
                            <td><?= $row['jenis_ternak_id']; ?></td>
                            <td><?= number_format($row['jumlah']); ?></td>
                            <td><?= $row['tahun']; ?></td>
                            <td>
                                <a href="/user/kabupaten/populasi/approve/<?= $row['id']; ?>" 
                                   class="btn btn-success btn-sm">
                                    ✔ Approve
                                </a>

                                <a href="/user/kabupaten/populasi/reject/<?= $row['id']; ?>" 
                                   class="btn btn-danger btn-sm">
                                    ✖ Reject
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Tidak ada data masuk
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- =========================
         DATA SELESAI
    ========================== -->
    <div class="card">
        <div class="card-header bg-success text-white">
            📊 Data Selesai Diproses
        </div>

        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Pemilik</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Tahun</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (!empty($selesai)): ?>
                    <?php foreach ($selesai as $row): ?>
                        <tr>
                            <td><?= $row['nama_pemilik_ternak']; ?></td>
                            <td><?= $row['jenis_ternak_id']; ?></td>
                            <td><?= number_format($row['jumlah']); ?></td>
                            <td><?= $row['tahun']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'disetujui'): ?>
                                    <span class="badge bg-success">Disetujui</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada data diproses
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?= $this->include('layout/user/footer') ?>