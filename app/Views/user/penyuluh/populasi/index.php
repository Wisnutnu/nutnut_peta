<?= $this->include('layout/user/header') ?>
<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>


<div class="main-container p-4">
    <h4>Data Populasi Ternak</h4>

    <!-- ALERT -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <!-- FILTER -->
    <form method="get" class="mb-3">
        <input type="number" name="tahun" 
               value="<?= $tahun ?? '' ?>" 
               placeholder="Filter Tahun"
               class="form-control w-25 d-inline">

        <button class="btn btn-secondary">Filter</button>
        <a href="/user/populasi" class="btn btn-light">Reset</a>
    </form>

    <!-- BUTTON TAMBAH -->
    <a href="/user/populasi/create" class="btn btn-primary mb-3">
        + Tambah Data
    </a>

    <!-- TABEL -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Ternak</th>
                <th>Jumlah</th>
                <th>Tahun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

            <?php if (!empty($populasi)): ?>
                <?php $no = 1; foreach ($populasi as $p): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $p['nama_jenis']; ?></td>
                        <td><?= number_format($p['jumlah']); ?></td>
                        <td><?= $p['tahun']; ?></td>

                        <!-- STATUS -->
                        <td>
                            <?php if ($p['status'] == 'draft'): ?>
                                <span class="badge bg-secondary">Draft</span>
                            <?php elseif ($p['status'] == 'diajukan'): ?>
                                <span class="badge bg-warning">Diajukan</span>
                            <?php elseif ($p['status'] == 'disetujui'): ?>
                                <span class="badge bg-success">Disetujui</span>
                            <?php elseif ($p['status'] == 'ditolak'): ?>
                                <span class="badge bg-danger">Ditolak</span>
                            <?php endif; ?>
                        </td>

                        <!-- AKSI -->
                        <td>

                            <?php if ($p['status'] == 'draft' || $p['status'] == 'ditolak'): ?>

                                <a href="/user/populasi/edit/<?= $p['id']; ?>" 
                                class="btn btn-warning btn-sm">Edit</a>

                                <a href="/user/populasi/delete/<?= $p['id']; ?>" 
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin hapus data ini?')">
                                Hapus
                                </a>

                            <?php endif; ?>

                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Belum ada data</td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>


    <?php 
    $total = array_sum(array_column($populasi, 'jumlah'));
    ?>
    <h5>Total Populasi: <?= number_format($total); ?></h5>

</div>

<?= $this->include('layout/user/footer') ?>