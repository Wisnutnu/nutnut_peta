<?= $this->include('layout/user/header') ?>
<?= $this->include('layout/user/navbar') ?>
<?= $this->include('layout/sidebar/sidebar_user') ?>

<div class="main-container">
    <div class="container-fluid py-4">
        <div class="content-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold">Data Pemotongan Tidak Tercatat</h5>

            <a href="/user/penyuluh/create" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Data
            </a>
        </div>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">

                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Tempat</th>
                            <th>Sapi Potong</th>
                            <th>Sapi Perah</th>
                            <th>Kerbau</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php if(!empty($data)): ?>
                        <?php foreach($data as $d): ?>
                            <tr>
                                <td><?= date('d-m-Y', strtotime($d['tanggal'])) ?></td>
                                <td><?= $d['nama_tempat'] ?></td>
                                <td><?= $d['sapi_potong'] ?></td>
                                <td><?= $d['sapi_perah'] ?></td>
                                <td><?= $d['kerbau'] ?></td>
                                <td>
                                    
                                    <?php if($d['status'] == 'pending'): ?>
                                        <span class="badge bg-warning text-dark">pending</span>
                                    <?php elseif($d['status'] == 'approved'): ?>
                                        <span class="badge bg-success">approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">reject</span>
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

            </div>
        </div>

    </div>
    </div>
</div>

<?= $this->include('layout/user/footer') ?>