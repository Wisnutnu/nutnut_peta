<?= $this->include('layout/user/header') ?>
<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>

<div class="main-container p-4">

    <h2 class="fw-bold">Data Populasi Provinsi</h2>
    <p class="text-muted">Data tabular penyuluh yang sudah disetujui</p>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kabupaten</th>
                        <th>Nama Pemilik</th>
                        <th>No Telp</th>
                        <th>Jenis Ternak</th>
                        <th>Jumlah</th>
                        <th>Tahun</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1; foreach ($populasi as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $p->nama_kabupaten ?></td>
                        <td><?= $p->nama_pemilik_ternak ?></td>
                        <td><?= $p->no_telp ?></td>
                        <td><?= $p->jenis_ternak_id ?></td>
                        <td><?= $p->jumlah ?></td>
                        <td><?= $p->tahun ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>

</div>

<?= $this->include('layout/user/footer') ?>