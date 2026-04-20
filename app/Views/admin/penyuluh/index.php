<?= $this->include('layout/admin/header') ?>
<?= $this->include('layout/admin/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_admin') ?>

<div class="main-container p-4">
    <div class="content-wrapper">
    <h4>Data Populasi Ternak</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemilik</th>
                <th>Jenis Ternak</th>
                <th>Jumlah</th>
                <th>Tahun</th>
                <th>Status</th>
                <th>Lokasi</th>
            </tr>
        </thead>

        <tbody>
        <?php $no = 1; foreach ($populasi as $row): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['nama_pemilik_ternak']; ?></td>
                <td><?= $row['nama_jenis']; ?></td>
                <td><?= number_format($row['jumlah']); ?></td>
                <td><?= $row['tahun']; ?></td>
                <td>
                    <?php if ($row['status'] == 'draft'): ?>
                        <span class="badge bg-secondary">Draft</span>
                    <?php elseif ($row['status'] == 'diajukan'): ?>
                        <span class="badge bg-warning">Diajukan</span>
                    <?php elseif ($row['status'] == 'disetujui'): ?>
                        <span class="badge bg-success">Disetujui</span>
                    <?php elseif ($row['status'] == 'ditolak'): ?>
                        <span class="badge bg-danger">Ditolak</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($row['latitude'] && $row['longitude']): ?>
                        <a href="https://www.google.com/maps?q=<?= $row['latitude']; ?>,<?= $row['longitude']; ?>" 
                        target="_blank" 
                        class="btn btn-sm btn-primary">
                            📍 Lihat Lokasi
                        </a>
                    <?php else: ?>
                        <span class="text-muted">Tidak ada lokasi</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<?= view('layout/admin/footer'); ?>